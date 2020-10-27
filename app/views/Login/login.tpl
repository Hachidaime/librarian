<!-- prettier-ignore -->
{extends 'Templates/blank.tpl'}

{block 'content'}
<div class="login-box">
  <div class="login-logo">
    <a href="{$smarty.const.BASE_URL}">
      {$smarty.const.PROJECT_NAME} <b>Console</b>
    </a>
  </div>
  <!-- /.login-logo -->
  <div class="card rounded-0">
    <div class="card-header bg-gradient-orange rounded-0">
      <h3 class="card-title text-dark">Log in to start your session</h3>
    </div>
    <div class="card-body login-card-body">
      <form
        action="{$smarty.const.BASE_URL}/login"
        method="post"
        id="login_form"
      >
        <div class="form-group row">
          <label for="usr_username" class="col-5 col-form-label">
            Username
          </label>
          <div class="col-7">
            <input
              type="text"
              class="form-control rounded-0"
              id="usr_username"
              name="usr_username"
            />
            <div class="invalid-feedback"></div>
          </div>
        </div>

        <div class="form-group row">
          <label for="usr_password" class="col-5 col-form-label">
            Password
          </label>
          <div class="col-7">
            <input
              type="password"
              class="form-control rounded-0"
              id="usr_password"
              name="usr_password"
            />
            <div class="invalid-feedback"></div>
          </div>
        </div>

        <div class="row">
          <!-- /.col -->
          <div class="col-12">
            <button
              type="button"
              class="btn btn-block btn-flat bg-gradient-olive"
              id="btn_login"
            >
              Log In
            </button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->
<!-- prettier-ignore -->
{/block}

{block 'script'}

{literal}
<script>
  $(document).ready(function () {
    $('#btn_login').click(() => {
      let data = $('#login_form').serialize()
      let url = '{/literal}{$smarty.const.BASE_URL}{literal}/login/submit'

      $('.form-control').removeClass('is-invalid').next().html('')
      let result = $.post(
        url,
        data,
        (res) => {
          if (!res.success) {
            $.each(res.msg, (id, error) => {
              $(`#${id}`).addClass('is-invalid').next().html(error)
            })
          } else {
            window.location = '{/literal}{$smarty.const.BASE_URL}{literal}'
          }
        },
        'JSON'
      )
    })

    $('.form-control').keypress((e) => {
      if (e.keyCode == 13) {
        e.preventDefault()
        $('#btn_login').click()
      }
    })
  })
</script>
<!-- prettier-ignore -->
{/literal}
{/block}
