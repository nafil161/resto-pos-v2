document.addEventListener("alpine:init", () => {
    Alpine.data("navigateLoader", () => ({
        loading: false,
        init() {
            window.addEventListener("livewire:navigate-start", () => {
                this.loading = true;
            });
            window.addEventListener("livewire:navigate-end", () => {
                this.loading = false;
            });
        },
    }));
});
