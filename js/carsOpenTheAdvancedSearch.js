function toggleAdvancedSearch() {
    const advancedSearch = document.getElementById('advanced-search');
    const toggleButton = document.getElementById('toggle-button');
    if (advancedSearch.classList.contains('hidden')) {
        advancedSearch.classList.remove('hidden');
        toggleButton.textContent = 'Скрыть';
    } else {
        advancedSearch.classList.add('hidden');
        toggleButton.textContent = 'Расширенный поиск';
    }
}