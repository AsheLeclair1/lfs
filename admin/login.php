<?php require_once('../config.php') ?>
<!DOCTYPE html>
<html lang="ru" class="" style="height: auto;">
 <?php require_once('inc/header.php') ?>
<body>
  <style>
    body{
      background-image: url("<?php echo validate_image($_settings->info('cover')) ?>");
      background-size:cover;
      background-repeat:no-repeat;
      backdrop-filter: brightness(.7);
      overflow-x:hidden;
    }
    .logo img {
        max-height: 55px;
        margin-right: 25px;
    }
    .logo span{
      color: #fff;
      text-shadow:0px 0px 10px #000;
    }
  </style>
  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="index.php" class="logo d-flex align-items-center w-auto">
                  <img src="<?= validate_image($_settings->info('logo')) ?>" alt="">
                  <span class="d-none d-lg-block text-center"><?= $_settings->info('name') ?></span>
                </a>
              </div><!-- Конец логотипа -->

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Вход в аккаунт</h5>
                    <p class="text-center small">Введите имя пользователя и пароль для входа</p>
                  </div>

                  <form class="row g-3 needs-validation" novalidate id="login-frm">

                    <div class="col-12">
                      <label for="yourUsername" class="form-label">Имя пользователя</label>
                      <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                        <input type="text" name="username" class="form-control" id="yourUsername" required> 
                        <div class="invalid-feedback">Пожалуйста, введите имя пользователя.</div>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Пароль</label>
                      <input type="password" name="password" class="form-control" id="yourPassword" required>
                      <div class="invalid-feedback">Пожалуйста, введите пароль!</div>
                    </div>

                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Войти</button>
                    </div>
                  </form>

                </div>
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>
  </main>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- jQuery -->
<script src="<?= base_url ?>assets/js/jquery-3.6.4.min.js"></script>
<script src="<?= base_url ?>assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="<?= base_url ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url ?>assets/vendor/chart.js/chart.umd.js"></script>
<script src="<?= base_url ?>assets/vendor/echarts/echarts.min.js"></script>
<script src="<?= base_url ?>assets/vendor/quill/quill.min.js"></script>
<script src="<?= base_url ?>assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="<?= base_url ?>assets/vendor/tinymce/tinymce.min.js"></script>
<script src="<?= base_url ?>assets/vendor/php-email-form/validate.js"></script>
<script src="<?= base_url ?>assets/js/main.js"></script>

<script>
  $(document).ready(function(){
    end_loader();
  })
</script>
</body>
</html>
