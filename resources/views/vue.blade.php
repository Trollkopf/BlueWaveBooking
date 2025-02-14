<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Página no encontrada</title>
    @vite('resources/js/app.js')
</head>
<body class="antialiased">
    <div id="app"></div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const app = Vue.createApp({
                template: `<NotFound />`,
                components: {
                    NotFound: Vue.defineAsyncComponent(() => import('/resources/js/Pages/NotFound.vue'))
                }
            });
            app.mount("#app");
        });
    </script>
</body>
</html>
