<!-- resources/views/components/navbarInf.blade.php -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<nav class="navbar-bottom">
    <a href="{{ route('home') }}" class="nav-icon {{ request()->routeIs('home') ? 'active' : '' }}">
        <i class="fas fa-home"></i>
    </a>
    <a href="{{ route('modelos3d') }}" class="nav-icon {{ request()->routeIs('modelos3d') ? 'active' : '' }}">
        <i class="fas fa-cube"></i>
    </a>
    <a href="{{ route('chatbot') }}" class="nav-icon {{ request()->routeIs('chatbot') ? 'active' : '' }}">
        <i class="fas fa-robot"></i>
    </a>
    <a href="{{ route('seguimiento') }}" class="nav-icon {{ request()->routeIs('seguimiento') ? 'active' : '' }}">
        <i class="fas fa-user"></i>
    </a>
</nav>

<style>
.navbar-bottom {
    position: fixed;
    bottom: 0;
    width: 100%;
    background:#202c34;
    display: flex;
    justify-content: space-around;
    padding: 12px 0;
}

.nav-icon {
    color: #A18CD1;
    font-size: 24px;
    text-decoration: none;
    transition: transform 0.2s ease;
}

.nav-icon:hover {
    transform: scale(1.2);
}

.nav-icon.active i {
    color: #22c55e; /* Verde para ruta activa */
}
</style>
