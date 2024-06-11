<?php require_once 'app/views/templates/headerPublic.php'?>
<main role="main" class="container">
    <div class="page-header" id="banner">
        <div class="row">
            <div class="col-lg-12">
                <h1>Sign-Up Form</h1>
            </div>
        </div>
    </div>

<div class="row">
    <div class="col-sm-auto">
    <form action="/create/index" method="post" >
    <fieldset>
      <div class="form-group">
        <label for="username">Username</label>
        <input required type="text" class="form-control" name="username">
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input required type="password" class="form-control" name="password">
          <small class="form-text text-muted">
              Password must be at least 8 characters long, contain both uppercase and lowercase letters, at least one number, and at least one special character.
          </small>
      </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input required type="password" class="form-control" name="confirm_password">
        </div>
            <br>
        <button type="submit" class="btn btn-primary">SignUp</button>
    </fieldset>
    </form> 
  </div>
</div>
    <?php require_once 'app/views/templates/footer.php' ?>
