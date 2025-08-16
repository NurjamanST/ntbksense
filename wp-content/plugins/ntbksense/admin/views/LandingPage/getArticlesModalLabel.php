<!-- START: Modal untuk Dapatkan Artikel -->
    <div class="modal fade" id="getArticlesModal" tabindex="-1" aria-labelledby="getArticlesModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="getArticlesModalLabel">Atur Jumlah URL Artikel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="get-articles-form">
                        <div class="mb-3">
                            <label for="article_count" class="form-label">Jumlah URL yang akan diambil</label>
                            <input type="number" class="form-control" id="article_count" name="article_count" value="10" min="1">
                            <small class="form-text text-muted">Masukkan -1 untuk mengambil semua artikel yang diterbitkan.</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="submit-get-articles">Dapatkan URL</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Modal -->