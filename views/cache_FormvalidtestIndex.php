<!DOCTYPE html>
<html>
  <head>
    <title><?php echo(htmlspecialchars(CONFIG['applicationName']. " - " . $title)); ?></title>
    <link rel="shortcut icon" href="https://cdn.glitch.com/7635e9c3-2015-4ec8-967a-16ca37cc9e55%2Ffavicon.ico" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="/static/style.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" integrity="sha384-xrRywqdh3PHs8keKZN+8zzc5TX0GRTLCcmivcbNJWm2rs5C8PRhcEn3czEjhAO9o" crossorigin="anonymous"></script>
    <script src="/static/custom.js"></script>
  </head>
  <body>
    <nav class="navbar navbar-expand-sm navbar-light bg-light">
        <a class="navbar-brand" href="#">
          <img src="https://cdn.glitch.com/7635e9c3-2015-4ec8-967a-16ca37cc9e55%2Ftodo.svg?v=1559671056411" width="30" height="30" class="d-inline-block align-top" alt="">&nbsp;<?php echo(htmlspecialchars(CONFIG['applicationName'])); ?></a>
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
          <li class="nav-item active">
            <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/about">About</a>
          </li>
        </ul>
        <ul class="navbar-nav">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownToolsLink" data-toggle="dropdown">
              <span class="material-icons" style="vertical-align:bottom">build</span> Tools
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="nav-link" href="https://glitch.com/edit/#!/remix/<?php echo(htmlspecialchars(getenv('PROJECT_DOMAIN'))); ?>">Remix</a>
              <a class="nav-link" onclick="post('/reset');" style="cursor:pointer">DB Reset</a>
              <a class="nav-link" href="/phpliteadmin.php" target="_blank" style="cursor:pointer">DB Admin</a>
            </div>
          </li>
        </ul>          
        <ul class="navbar-nav">
<?php  if (isLoggedIn()): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownUserLink" data-toggle="dropdown">
              <span class="material-icons" style="vertical-align:bottom">account_circle</span> <?php echo(htmlspecialchars($_SESSION['user']['firstName'])); ?>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="/user/edit/<?php echo(htmlspecialchars($_SESSION['user']->id)); ?>">Edit profile</a>
              <a class="dropdown-item" href="/user/change_password/<?php echo(htmlspecialchars($_SESSION['user']->id)); ?>">Change password</a>
<?php  if (Authenticator::instance()->can('admin_page')) : ?>
              <a class="dropdown-item" href="/admin">Admin pages</a>
<?php  endif; ?>

              <a class="dropdown-item" href="/user/logout">Logout</a>
            </div>
          </li>
<?php  else: ?>
          <li class="nav-item">
            <a class="nav-link" onclick="get('/user/login');" style="cursor:pointer">Login</a>
          </li>
<?php  endif; ?>
        </ul>
    </nav>
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="display-4"><?php echo(htmlspecialchars(CONFIG['applicationName']. " - " . $title)); ?></h1>
          <p class="lead"><?php echo(htmlspecialchars(CONFIG['leadDescription'])); ?></p>
          <p><em>Author: <?php echo(htmlspecialchars(CONFIG['authorName'])); ?></em></p>
          <hr>
        </div>
      </div>

<?php  if (isset($errors) && $errors): ?>
<div class="row">
  <div class="col-lg-12">
    <div class="alert alert-danger">
      Please fix the following errors:
      <ul class="mb-0">
<?php  foreach ($errors as $error): ?>
        <li><?php echo($error); ?></li>
<?php  endforeach; ?>
      </ul>
    </div>
  </div>
</div>
<?php  endif;?>
      
<?php  if (isset($_SESSION['flash'])): ?>
<div class="alert alert-success alert-dismissible flash-message" role="alert" id="flash">
  <?php echo($_SESSION['flash']); ?>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<script type="text/javascript">
  $(document).ready(function() {
    $("div.flash-message").fadeTo(1000,1).delay(2000).fadeOut(1000);
  });
</script>
<?php  
   unset($_SESSION['flash']);
   endif;
?>


<div class="row">
  <div class="col-lg-12">
    <h1><?php echo(htmlspecialchars($title)); ?></h1>
    
    <form action="/formvalidtest/index" method="post">
      <div class="form-group">
        <label for="required">A required field</label>
        <input type="text" min="1" id="required" name="form[required]" class="form-control" placeholder="Enter something" value="<?php echo(htmlspecialchars($this->value($form['required']))); ?>" />
        <small class="text-danger"><?php echo(htmlspecialchars($this->value($errors['required']))); ?></small>
      </div>
      <div class="form-group">
        <label for="phone">A phone number</label>
        <input type="text" min="1" id="phone" name="form[phone]" class="form-control" placeholder="Enter something" value="<?php echo(htmlspecialchars($this->value($form['phone']))); ?>" />
        <small class="text-danger"><?php echo(htmlspecialchars($this->value($errors['phone']))); ?></small>
      </div>
      <div class="form-group">
        <label for="email">An email address</label>
        <input type="text" min="1" id="email" name="form[email]" class="form-control" placeholder="Enter something" value="<?php echo(htmlspecialchars($this->value($form['email']))); ?>" />
        <small class="text-danger"><?php echo(htmlspecialchars($this->value($errors['email']))); ?></small>
      </div>
      <div class="form-group">
        <label for="integer">An integer</label>
        <input type="text" min="1" id="integer" name="form[integer]" class="form-control" placeholder="Enter something" value="<?php echo(htmlspecialchars($this->value($form['integer']))); ?>" />
        <small class="text-danger"><?php echo(htmlspecialchars($this->value($errors['integer']))); ?></small>
      </div>
      <div class="form-group">
        <label for="float">A floating point number</label>
        <input type="text" min="1" id="float" name="form[float]" class="form-control" placeholder="Enter something" value="<?php echo(htmlspecialchars($this->value($form['float']))); ?>" />
        <small class="text-danger"><?php echo(htmlspecialchars($this->value($errors['float']))); ?></small>
      </div>
      <div class="form-group">
        <label for="money">An amount of money</label>
        <input type="text" min="1" id="money" name="form[money]" class="form-control" placeholder="Enter something" value="<?php echo(htmlspecialchars($this->value($form['money']))); ?>" />
        <small class="text-danger"><?php echo(htmlspecialchars($this->value($errors['money']))); ?></small>
      </div>
      <div class="form-group">
        <label for="password">A strong password</label>
        <input type="text" min="1" id="password" name="form[password]" class="form-control" placeholder="Enter something" value="<?php echo(htmlspecialchars($this->value($form['password']))); ?>" />
        <small class="text-danger"><?php echo(htmlspecialchars($this->value($errors['password']))); ?></small>
      </div>
      <div class="form-group">
        <label for="password2">Must match above password</label>
        <input type="text" min="1" id="password2" name="form[password2]" class="form-control" placeholder="Enter something" value="<?php echo(htmlspecialchars($this->value($form['password2']))); ?>" />
        <small class="text-danger"><?php echo(htmlspecialchars($this->value($errors['password2']))); ?></small>
      </div>
      <div class="form-group">
        <label for="between" class="control-label">Must be between 25 and 555</label>
        <input type="text" min="1" id="between" name="form[between]" class="form-control" placeholder="Enter something" value="<?php echo(htmlspecialchars($this->value($form['between']))); ?>" />
        <small class="text-danger"><?php echo(htmlspecialchars($this->value($errors['between']))); ?></small>
      </div>
      <div class="form-group mt-4">
        <button type="submit" class="btn btn-primary">Submit</button>
        <button class="btn btn-secondary" onclick="get('/index')">Cancel</button>
      </div>
    </form>
  </div>
</div>
          
    </div>
    <footer class="footer">
      <div class="container">
        <span class="text-muted">WEBD 236 examples copyright &copy; 2019 <a href="https://www.franklin.edu/">Franklin University</a>.</span>
      </div>
    </footer>
  </body>
</html> 