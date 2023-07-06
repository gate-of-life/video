<script setup>
</script>

<template>
    <nav id="navbarExample" class="navbar navbar-expand-lg fixed-top navbar-light bg-white border-bottom border-grey"
        aria-label="Main navigation">
        <div class="container">
            <Link class="navbar-brand logo-text" :href="route('welcome')">
            <img class="d-none d-md-block" src="/image/logo.jpg" height="60" alt="">
            <img class="d-md-none" src="/image/logo.jpg" height="45" alt="">
            </Link>

            <div id="navbarsExampleDefault">
                <ul class="navbar-nav flex-row gap-4">
                    <li class="nav-item">
                        <Link class="nav-link" :href="route('welcome')" :class="{ 'active': $page.url == '/' }">Home</Link>
                    </li>
                    <li class="nav-item">
                        <Link class="nav-link" :href="route('videos.index')"
                            :class="{ 'active': $page.url.startsWith('/videos') }">Videos</Link>
                    </li>
                    <li class="nav-item">
                        <Link class="nav-link" :href="route('channels.index')"
                            :class="{ 'active': $page.url.startsWith('/channels') }">Channels</Link>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://gateoflife.net" target="_blank">Main
                            <i class="fa-solid fa-square-arrow-up-right"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<style scoped>
.navbar .navbar-brand {
    padding-top: 0.25rem;
    padding-bottom: 0.25rem;
}

.navbar .logo-image img {
    width: 126px;
    height: 32px;
}

.nav-link {
    padding-bottom: 0.2rem;
}

.nav-link {
    font-size: 1.1rem;
}

.nav-link.active {
    position: relative;
}

.nav-link.active::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 70%;
    height: 2px;
    background-color: #0c76ec;
}
</style>