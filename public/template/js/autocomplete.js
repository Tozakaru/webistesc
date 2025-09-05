document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('searchInput');
    const resultsBox = document.getElementById('autocompleteResults');
    
    let timeout = null;

    input.addEventListener('input', function () {
        clearTimeout(timeout);

        let query = this.value.trim();
        if (query.length < 1) {
            resultsBox.style.display = 'none';
            return;
        }

        // Deteksi URL halaman
        let endpoint = '';
        if (window.location.pathname.includes('logaktivitas')) {
            endpoint = '{{ route("autocomplete.log") }}';
        } else if (window.location.pathname.includes('account-request')) {
            endpoint = '{{ route("autocomplete.account.request") }}';
        } else if (window.location.pathname.includes('account-list')) {
            endpoint = '{{ route("autocomplete.account.list") }}';
        }

        timeout = setTimeout(() => {
            fetch(endpoint + '?query=' + query)
                .then(res => res.json())
                .then(data => {
                    if (data.length > 0) {
                        resultsBox.innerHTML = '';
                        data.forEach(item => {
                            const div = document.createElement('div');
                            div.classList.add('px-3', 'py-2', 'search-suggestion', 'cursor-pointer');
                            div.style.cursor = 'pointer';
                            div.textContent = item;
                            div.addEventListener('click', () => {
                                input.value = item;
                                input.form.submit();
                            });
                            resultsBox.appendChild(div);
                        });
                        resultsBox.style.display = 'block';
                    } else {
                        resultsBox.innerHTML = '<div class="px-3 py-2 text-muted">Tidak ditemukan</div>';
                        resultsBox.style.display = 'block';
                    }
                });
        }, 300);
    });

    // Tutup jika klik di luar
    document.addEventListener('click', function (e) {
        if (!resultsBox.contains(e.target) && e.target !== input) {
            resultsBox.style.display = 'none';
        }
    });
});