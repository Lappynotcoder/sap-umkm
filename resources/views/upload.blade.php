<form action="/proses-upload" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="file_excel">Unggah Template Excel UMKM:</label>
    <input type="file" name="file_excel" accept=".xlsx, .csv" required>
    <button type="submit">Analisis Sekarang</button>
</form>
