<!-- Header -->
            <header class="header">
                <h1 class="page-title">Admin</h1>
                <div class="header-actions">
                    <button class="theme-toggle" title="Chuyển theme">🌙</button>
                    <div class="user-menu">
                        <div class="user-avatar">
                            <img src="{{ auth()->user()->avatar ? asset(auth()->user()->avatar) : asset('images/default-avatar.png') }}" alt="Avatar" class="avatar">
                        </div>
                        <span>{{ auth()->user()->name }}</span>
                    </div>
                </div>
            </header>