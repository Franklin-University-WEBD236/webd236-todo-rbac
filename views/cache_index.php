<!DOCTYPE html>
<html>
  <head>
    <title>To do list model 2</title>
    <link href="/static/style.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" integrity="sha384-xrRywqdh3PHs8keKZN+8zzc5TX0GRTLCcmivcbNJWm2rs5C8PRhcEn3czEjhAO9o" crossorigin="anonymous"></script>
    <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
    <script src="parts/custom.js"></script>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-lg-8 offset-2">
          <h1 class="display-4">To do list model 2</h1>
          <p class="lead">Keep track of things that you need to do.</p>
          <p><em>Author: <a href="https://www.franklin.edu/about-us/faculty-staff/faculty-profiles/whittakt">Todd Whittaker</a></em></p>
          <hr>
        </div>
      </div>

<div class="row">
  <div class="col-lg-8 offset-2">
    <h1><?php echo($title); ?></h1>

      <form action="/todo/add" method="post">
      <div class="form-group">
        <label for="description">Add a new todo.</label>
        <input type="text" min="1" id="description" name="description" class="form-control" placeholder="Enter description" value=""/>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </form>

  </div>
</div>

<div class="row">
  <div class="col-lg-8 offset-2">
    <h2>Current To Do:</h2>
      
    <table class="table table-striped">
      <colgroup>
        <col class="col-md-1">
        <col class="col-md-7">
      </colgroup>
      <thead class="thead-dark">
        <tr>
          <th>Description</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
<?php  foreach ($todos as $todo) : ?>
        <tr>
          <td><?php echo "{$todo['description']}" ?></td>
          <td>
            <div class="btn-toolbar">
              <button class="btn btn-secondary d-flex justify-content-center align-content-between mr-1 addclickhandler" action="view.php" stu_num="<?php echo "{$todo['id']}"?>"><span class="material-icons">visibility</span>&nbsp;View</button>
              <button class="btn btn-secondary d-flex justify-content-center align-content-between mr-1 addclickhandler" action="edit.php" stu_num="<?php echo "{$todo['id']}"?>"><span class="material-icons">mode_edit</span>&nbsp; Edit</button>
              <button class="btn btn-secondary d-flex justify-content-center align-content-between addclickhandler" action="delete.php" stu_num="<?php echo "{$todo['id']}"?>"><span class="material-icons">delete</span>&nbsp;Delete</button>
            </div>
          </td>
        </tr>
<?php  endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="row">
  <div class="col-lg-8 offset-2">
    <h2>Past To Do:</h2>
    <table class="table table-striped">
      <thead class="thead-dark">
        <tr>
          <th>Description</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
<?php  foreach ($dones as $todo) : ?>
        <tr>
          <td class="col-md-2"><?php echo "{$todo['description']}" ?></td>
          <td>
            <div class="btn-toolbar">
              <button class="btn btn-secondary d-flex justify-content-center align-content-between mr-1 addclickhandler" action="view.php" stu_num="<?php echo "{$todo['id']}"?>"><span class="material-icons">visibility</span>&nbsp;View</button>
              <button class="btn btn-secondary d-flex justify-content-center align-content-between mr-1 addclickhandler" action="edit.php" stu_num="<?php echo "{$todo['id']}"?>"><span class="material-icons">mode_edit</span>&nbsp; Edit</button>
              <button class="btn btn-secondary d-flex justify-content-center align-content-between addclickhandler" action="delete.php" stu_num="<?php echo "{$todo['id']}"?>"><span class="material-icons">delete</span>&nbsp;Delete</button>
            </div>
          </td>
        </tr>
<?php  endforeach; ?>
      </tbody>
    </table>
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