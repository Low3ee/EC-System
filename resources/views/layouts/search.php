<div class="relative w-full max-w-lg mx-auto search-component">
    <div class="relative">
        <input 
            type="text" 
            id="navbar-search-input"
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm pl-4 pr-10 py-2" 
            placeholder="Search tenants, rooms, invoices..."
            autocomplete="off"
        >
        <div id="search-spinner" class="absolute inset-y-0 right-0 flex items-center pr-3 hidden">
            <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>
    
    <!-- Dropdown Results -->
    <div id="navbar-search-results" class="absolute z-50 w-full mt-1 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 hidden">
        <div class="py-1" id="search-results-container">
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('navbar-search-input');
    const resultsDropdown = document.getElementById('navbar-search-results');
    const resultsContainer = document.getElementById('search-results-container');
    const spinner = document.getElementById('search-spinner');
    let timeout = null;

    input.addEventListener('input', function(e) {
        clearTimeout(timeout);
        const query = e.target.value;

        if (query.length < 2) {
            resultsDropdown.classList.add('hidden');
            return;
        }

        spinner.classList.remove('hidden');

        timeout = setTimeout(() => {
            axios.get('/search/results', { params: { q: query } })
                .then(response => {
                    const results = response.data;
                    resultsContainer.innerHTML = '';

                    if (results.length > 0) {
                        results.forEach(result => {
                            const el = document.createElement('a');
                            el.href = result.url;
                            el.className = 'block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100';
                            el.innerHTML = `
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="font-medium text-gray-900">${result.title}</div>
                                        <div class="text-gray-500 text-xs">${result.description}</div>
                                    </div>
                                    <span class="text-xs bg-indigo-100 text-indigo-800 rounded-full px-2 py-0.5 whitespace-nowrap ml-2">${result.type}</span>
                                </div>
                            `;
                            resultsContainer.appendChild(el);
                        });
                        resultsDropdown.classList.remove('hidden');
                    } else {
                        resultsDropdown.classList.add('hidden');
                    }
                })
                .catch(err => console.error(err))
                .finally(() => spinner.classList.add('hidden'));
        }, 300);
    });

    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !resultsDropdown.contains(e.target)) {
            resultsDropdown.classList.add('hidden');
        }
    });
});
</script>