const setup = () => {
    //mengatur mode gelap/terang
    const getTheme = () => {
        if (window.localStorage.getItem('dark')) {
            return JSON.parse(window.localStorage.getItem('dark'))
        }
        return !!window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
    }

    const setTheme = (value) => {
        window.localStorage.setItem('dark', value)
    }

    return {
        isDark: getTheme(),

        toggleTheme() {
            this.isDark = !this.isDark
            setTheme(this.isDark)
        },

        //menampilkan/menyembunyikan sidebar
        isSidebarOpen: JSON.parse(window.localStorage.getItem('sidebar')),
        toggleSidebarMenu() {
            this.isSidebarOpen = !this.isSidebarOpen
            window.localStorage.setItem('sidebar', this.isSidebarOpen)
        },

        //menampilkan/menyembunyikan modal dan dialog konfirmasi
        isModalOpen: false,
        isConfirmOpen: false,
    }
}
