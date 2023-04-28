<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand" href="/admin">QrCodeGenerator</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="/admin">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/admin/qrcodes">Pastas de Documentação</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/admin/categories">Gestão de Categorias</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/admin/users">Gestão de Usuários</a>
          </li>
        </ul>
        <div class="d-flex">
        <li class="nav-item dropdown d-flex">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              {{ $user->user_name }}
            </a>
            <ul class="dropdown-menu" id="signOut">
              <form method="POST" style="margin:0" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item">Terminar Sessão</button>
              </form>
            </ul>
          </li>
        </div>
      </div>
    </div>
  </nav>
  <style>
    #navbarTogglerDemo02 {
      /* Make the dropdown a bit to the left */
      margin-right : 10px;
    }

    .dropdown-menu {
      transform: translate3d(-70%, 0, 0);
    }

    .navbar {
        border-bottom: 5px solid #707070;
    }
  </style>