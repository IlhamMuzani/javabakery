<form action="{{ route('categories.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="category">Kategori</label>
        <select name="category" id="category" class="form-control">
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="subcategory">Subkategori</label>
        <select name="subcategory" id="subcategory" class="form-control">
            <!-- Subkategori akan diisi menggunakan JavaScript berdasarkan pilihan kategori -->
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>

<script>
    // Script untuk mengisi dropdown subkategori berdasarkan kategori yang dipilih
    $('#category').on('change', function() {
        var category_id = $(this).val();
        $.ajax({
            url: '/api/subcategories/' + category_id,
            type: 'GET',
            success: function(data) {
                $('#subcategory').empty();
                $.each(data, function(index, subcategory) {
                    $('#subcategory').append('<option value="' + subcategory.id + '">' + subcategory.name + '</option>');
                });
            }
        });
    });
</script>
