import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

const normalize = (value = '') => value.toLowerCase().trim();

document.addEventListener('alpine:init', () => {
    Alpine.data('siteNav', () => ({
        mobileOpen: false,
        openMenu: null,
        searchCompressed: false,
        searchFocused: false,
        init() {
            this.handleScroll();

            this.onScroll = () => this.handleScroll();
            window.addEventListener('scroll', this.onScroll, { passive: true });
        },
        handleScroll() {
            this.searchCompressed = window.scrollY > 72;
        },
        toggleMenu(menu) {
            this.openMenu = this.openMenu === menu ? null : menu;
        },
    }));

    Alpine.data('galleryBrowser', (items = []) => ({
        items,
        query: '',
        selectedCategory: 'All',
        activeItem: null,
        get availableCategories() {
            return ['All', ...new Set(this.items.map((item) => item.category))];
        },
        get filteredItems() {
            return this.items.filter((item) => {
                const matchesCategory = this.selectedCategory === 'All' || item.category === this.selectedCategory;
                const haystack = `${item.title} ${item.summary} ${item.category}`;
                const matchesQuery = normalize(haystack).includes(normalize(this.query));

                return matchesCategory && matchesQuery;
            });
        },
        open(item) {
            this.activeItem = item;
            document.body.classList.add('overflow-hidden');
        },
        close() {
            this.activeItem = null;
            document.body.classList.remove('overflow-hidden');
        },
    }));

    Alpine.data('forumDirectory', (topics = []) => ({
        topics,
        query: '',
        selectedCategory: 'All',
        get availableCategories() {
            return ['All', ...new Set(this.topics.map((topic) => topic.category))];
        },
        get filteredTopics() {
            return this.topics.filter((topic) => {
                const matchesCategory = this.selectedCategory === 'All' || topic.category === this.selectedCategory;
                const haystack = `${topic.title} ${topic.summary} ${topic.starter_name} ${(topic.tags || []).join(' ')}`;
                const matchesQuery = normalize(haystack).includes(normalize(this.query));

                return matchesCategory && matchesQuery;
            });
        },
    }));

    Alpine.data('letterArchive', (letters = []) => ({
        letters,
        query: '',
        selectedCategory: 'All',
        selectedYear: 'All',
        get availableCategories() {
            return ['All', ...new Set(this.letters.map((letter) => letter.category))];
        },
        get availableYears() {
            return ['All', ...new Set(this.letters.map((letter) => letter.year).filter(Boolean))];
        },
        get filteredLetters() {
            return this.letters.filter((letter) => {
                const matchesCategory = this.selectedCategory === 'All' || letter.category === this.selectedCategory;
                const matchesYear = this.selectedYear === 'All' || letter.year === this.selectedYear;
                const haystack = `${letter.title} ${letter.summary} ${letter.topic} ${letter.category}`;
                const matchesQuery = normalize(haystack).includes(normalize(this.query));

                return matchesCategory && matchesYear && matchesQuery;
            });
        },
        get groupedLetters() {
            const groups = this.filteredLetters.reduce((carry, letter) => {
                const year = letter.year || 'Undated';

                if (!carry[year]) {
                    carry[year] = [];
                }

                carry[year].push(letter);

                return carry;
            }, {});

            return Object.entries(groups).sort(([left], [right]) => {
                if (left === 'Undated') {
                    return 1;
                }

                if (right === 'Undated') {
                    return -1;
                }

                return Number(right) - Number(left);
            });
        },
    }));

    Alpine.data('projectCatalog', (projects = []) => ({
        projects,
        query: '',
        selectedCategory: 'All',
        activeProject: null,
        init() {
            const hash = window.location.hash;

            if (hash.startsWith('#project-')) {
                const slug = hash.replace('#project-', '');
                const matched = this.projects.find((project) => project.slug === slug);

                if (matched) {
                    this.open(matched, false);
                }
            }
        },
        get availableCategories() {
            return ['All', ...new Set(this.projects.map((project) => project.category))];
        },
        get filteredProjects() {
            return this.projects.filter((project) => {
                const matchesCategory = this.selectedCategory === 'All' || project.category === this.selectedCategory;
                const haystack = `${project.title} ${project.summary} ${project.description} ${project.category} ${project.year ?? ''}`;
                const matchesQuery = normalize(haystack).includes(normalize(this.query));

                return matchesCategory && matchesQuery;
            });
        },
        open(project, updateHash = true) {
            this.activeProject = project;
            document.body.classList.add('overflow-hidden');

            if (updateHash) {
                window.location.hash = `project-${project.slug}`;
            }
        },
        close() {
            this.activeProject = null;
            document.body.classList.remove('overflow-hidden');

            if (window.location.hash.startsWith('#project-')) {
                history.replaceState(null, '', `${window.location.pathname}${window.location.search}`);
            }
        },
    }));
});

Alpine.start();
