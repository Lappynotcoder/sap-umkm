"""
SAP-UMKM — Mesin Komputasi Analitik (Fase 4)
Menerima absolute path file Excel/CSV via sys.argv,
lalu mencetak hasil analisis dalam format JSON ke stdout.
"""

import sys
import json
import pandas as pd


# ──────────────────────────────────────────────
# FUNGSI 1: Ingesti Data (Data I/O)
# ──────────────────────────────────────────────
def ingest_data(file_path: str) -> pd.DataFrame:
    """
    Membaca file Excel atau CSV dari absolute path
    yang dikirim oleh Laravel melalui sys.argv.
    """
    if file_path.endswith(".csv"):
        df = pd.read_csv(file_path)
    else:
        df = pd.read_excel(file_path, engine="openpyxl")

    # Normalisasi nama kolom: strip whitespace & lowercase
    df.columns = df.columns.str.strip().str.lower()
    return df


# ──────────────────────────────────────────────
# FUNGSI 2: Agregasi Kategorikal
# ──────────────────────────────────────────────
def agregasi_kategorikal(df: pd.DataFrame) -> dict:
    """
    Melakukan filtering dan penjumlahan berdasarkan
    kata kunci di kolom 'kategori':
      - Pemasukan
      - HPP (Harga Pokok Penjualan)
      - Operasional
    """
    required_cols = {"kategori", "nominal"}
    if not required_cols.issubset(df.columns):
        raise ValueError(
            f"Kolom wajib tidak ditemukan. "
            f"Kolom yang tersedia: {list(df.columns)}. "
            f"Wajib ada: {list(required_cols)}"
        )

    df["nominal"] = pd.to_numeric(df["nominal"], errors="coerce").fillna(0)
    cat = df["kategori"].str.strip().str.lower()

    total_pemasukan    = df.loc[cat == "pemasukan",    "nominal"].sum()
    total_hpp          = df.loc[cat == "hpp",          "nominal"].sum()
    total_operasional  = df.loc[cat == "operasional",  "nominal"].sum()

    # Detail per baris (untuk tabel konfirmasi di dashboard)
    detail_rows = df[["kategori", "nominal"]].copy()
    if "keterangan" in df.columns:
        detail_rows = df[["kategori", "keterangan", "nominal"]].copy()
    detail_rows["nominal"] = detail_rows["nominal"].astype(float)

    return {
        "total_pemasukan":   float(total_pemasukan),
        "total_hpp":         float(total_hpp),
        "total_operasional": float(total_operasional),
        "detail":            detail_rows.to_dict(orient="records"),
    }


# ──────────────────────────────────────────────
# FUNGSI 3: Kalkulasi Profitabilitas
# ──────────────────────────────────────────────
def kalkulasi_profitabilitas(total_pemasukan: float,
                             total_hpp: float,
                             total_operasional: float) -> dict:
    """
    Menghitung:
      - Laba Kotor  = Pemasukan - HPP
      - Laba Bersih = Laba Kotor - Operasional
      - Margin Kotor (%) dan Margin Bersih (%)
    """
    laba_kotor  = total_pemasukan - total_hpp
    laba_bersih = laba_kotor - total_operasional

    margin_kotor  = (laba_kotor  / total_pemasukan * 100) if total_pemasukan else 0
    margin_bersih = (laba_bersih / total_pemasukan * 100) if total_pemasukan else 0

    # Break Even Point (sederhana): total biaya / (1 - rasio HPP terhadap pemasukan)
    rasio_hpp = (total_hpp / total_pemasukan) if total_pemasukan else 0
    bep = (total_operasional / (1 - rasio_hpp)) if (1 - rasio_hpp) > 0 else 0

    return {
        "laba_kotor":     float(laba_kotor),
        "laba_bersih":    float(laba_bersih),
        "margin_kotor":   round(float(margin_kotor),  2),
        "margin_bersih":  round(float(margin_bersih), 2),
        "break_even":     float(bep),
    }


# ──────────────────────────────────────────────
# FUNGSI 4: Standardisasi Output (JSON Printer)
# ──────────────────────────────────────────────
def cetak_json(payload: dict) -> None:
    """
    Membungkus payload ke dalam struktur JSON dan
    mencetak ke stdout agar dapat ditangkap Laravel.
    """
    print(json.dumps(payload, ensure_ascii=False))


# ──────────────────────────────────────────────
# ENTRYPOINT
# ──────────────────────────────────────────────
if __name__ == "__main__":
    try:
        if len(sys.argv) < 2:
            raise ValueError("Absolute path file wajib dikirim sebagai argumen pertama.")

        file_path = sys.argv[1]

        # Fase 4.1 — Ingesti
        df = ingest_data(file_path)

        # Fase 4.2 — Agregasi
        agregat = agregasi_kategorikal(df)

        # Fase 4.3 — Kalkulasi Profitabilitas
        profit = kalkulasi_profitabilitas(
            agregat["total_pemasukan"],
            agregat["total_hpp"],
            agregat["total_operasional"],
        )

        # Fase 4.4 — Output JSON
        output = {
            "status":            "success",
            "total_pemasukan":   agregat["total_pemasukan"],
            "total_hpp":         agregat["total_hpp"],
            "total_operasional": agregat["total_operasional"],
            "laba_kotor":        profit["laba_kotor"],
            "laba_bersih":       profit["laba_bersih"],
            "margin_kotor":      profit["margin_kotor"],
            "margin_bersih":     profit["margin_bersih"],
            "break_even":        profit["break_even"],
            "detail":            agregat["detail"],
        }
        cetak_json(output)

    except Exception as e:
        cetak_json({
            "status": "error",
            "message": str(e),
        })
