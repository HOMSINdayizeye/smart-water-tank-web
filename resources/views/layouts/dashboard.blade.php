<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') Smart Water Tank</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        body {
            display: flex; /* Use flexbox for main layout */
            min-height: 100vh;
            overflow-x: hidden; /* Prevent horizontal scroll */
        }
        .dashboard-wrapper {
            display: flex;
            width: 100%;
        }
        .sidebar {
            width: 250px; /* Fixed sidebar width */
            background-color: #e2e8f0; /* Light grey for sidebar */
            padding: 1.5rem 1rem;
            flex-shrink: 0; /* Prevent shrinking */
        }
        .main-content {
            flex-grow: 1; /* Allow main content to take remaining space */
            padding: 1.5rem;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="dashboard-wrapper">
        {{-- Sidebar --}}
        <div class="sidebar">
            @yield('sidebar')
        </div>

        {{-- Main Content Area --}}
        <div class="main-content" id="dashboardContentArea">
             <main class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mainContentArea = document.getElementById('dashboardContentArea');
            const sidebarLinks = document.querySelectorAll('.sidebar-menu a');

            sidebarLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    // Prevent default link behavior only for links with href not equal to '#'
                    if (this.href && this.href !== window.location.href + '#') {
                         e.preventDefault();

                        const url = this.href;

                        // Fetch the new page content
                        fetch(url)
                            .then(response => response.text())
                            .then(html => {
                                // Parse the HTML to find the main content and title
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');

                                const newMainContent = doc.getElementById('dashboardContentArea');
                                const newTitle = doc.querySelector('title');

                                if (newMainContent) {
                                    // Replace the current main content
                                    mainContentArea.innerHTML = newMainContent.innerHTML;

                                    // Update the page title
                                    if (newTitle) {
                                        document.title = newTitle.textContent;
                                    }

                                    // Update the browser's history and URL
                                    history.pushState({}, '', url);
                                }
                                // Note: Scripts within the loaded content might not execute automatically.
                                // More complex solutions (like manually executing scripts or using a JS framework)
                                // would be needed for dynamic functionality within loaded content.
                            })
                            .catch(err => {
                                console.error('Failed to load content:', err);
                                // Optionally display an error message to the user
                            });
                    }
                });
            });

             // Handle browser back/forward buttons
            window.addEventListener('popstate', function(event) {
                // Reload the content for the current URL
                 fetch(window.location.href)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newMainContent = doc.getElementById('dashboardContentArea');
                         const newTitle = doc.querySelector('title');

                        if (newMainContent) {
                             mainContentArea.innerHTML = newMainContent.innerHTML;
                             if (newTitle) {
                                 document.title = newTitle.textContent;
                            }
                        }
                    })
                    .catch(err => {
                        console.error('Failed to load content on popstate:', err);
                    });
            });
        });
    </script>

    @stack('scripts')
</body>
</html> 