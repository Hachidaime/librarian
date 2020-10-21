<nav
  class="main-header navbar navbar-expand-md navbar-dark navbar-navy sticky-top"
>
  <div class="container">
    <button
      class="navbar-toggler order-1"
      type="button"
      data-toggle="collapse"
      data-target="#navbarCollapse"
      aria-controls="navbarCollapse"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <span class="fas fa-th my-1"></span>
    </button>

    <div class="collapse navbar-collapse order-3" id="navbarCollapse">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="{$smarty.const.BASE_URL}">Dashboard</a>
        </li>
        {if $smarty.session.USER.usr_is_master eq 1}
        <li class="nav-item dropdown">
          <a
            id="dropdownSubMenu1"
            href="#"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
            class="nav-link dropdown-toggle"
          >
            Master
          </a>
          <ul
            aria-labelledby="dropdownSubMenu1"
            class="dropdown-menu border-0 shadow"
          >
            <li>
              <a href="{$smarty.const.BASE_URL}/user" class="dropdown-item">
                User
              </a>
            </li>
          </ul>
        </li>
        <!-- prettier-ignore -->
        {/if}
      </ul>
    </div>

    <!-- Right navbar links -->
    <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
      <div class="user-panel p-0 m-0 d-flex">
        <div class="info m-0 p-0">
          <a
            href="{$smarty.const.BASE_URL}/profile"
            class="d-block nav-link"
            title="Profil"
          >
            <i class="fas fa-user-circle"></i>
          </a>
        </div>
      </div>

      <li class="nav-item">
        <a
          class="nav-link"
          href="{$smarty.const.BASE_URL}/logout"
          title="Logout"
        >
          <i class="fas fa-sign-out-alt"></i>
        </a>
      </li>
    </ul>
  </div>
</nav>
