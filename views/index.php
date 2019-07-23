%% views/header.html %%

<div class="row">
  <div class="col-lg-12">

[[if (!isLoggedIn()): ]]
  <h1>Welcome to the To Do List!</h1>
  <p>The To Do List is your one stop for keeping track of all the things you need to do in life. You can
    easily create, modify, delete, and mark as done anything you choose to enter into this system. However, you can only
    vier or enter things into the system if you are logged in. So, feel free to <a href="@@user/login@@">log in</a>
    or <a href="@@user/register@@">register</a> for an account.</p>
  <div class="form-group mt-4">
    <button class="btn btn-primary" onclick="get('@@user/login@@')">Log in</button>
    <button class="btn btn-secondary" onclick="get('@@user/register@@')">Register</button>
  </div>

[[ else: ]]
  
    <form action="@@todo/add@@" method="post">
      <div class="form-group">
        <label for="description">Add a new todo.</label>
        <input type="text" min="1" id="description" name="form[description]" class="form-control" placeholder="Enter description" value=""/>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </form>

  </div>
</div>

<div class="row">
  <div class="col-lg-12">
    <h2>Current To Do:</h2>
      
    <table class="table table-striped" frame="box">
      <thead class="thead-dark">
        <tr>
          <th>Description</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
[[ foreach ($todos as $todo) : ]]
        <tr>
          <td class="align-middle"><?php echo "{$todo['description']}" ?></td>
          <td>
            <div class="btn-toolbar align-middle float-right">
              <button class="btn btn-success d-flex justify-content-center align-content-between mr-1" onclick="post('@@todo/done/{{$todo['id']}}@@')"><span class="material-icons">done</span></button>
              <button class="btn btn-primary d-flex justify-content-center align-content-between mr-1" onclick="get('@@todo/edit/{{$todo['id']}}@@')"><span class="material-icons">create</span></button>
              <button class="btn btn-danger d-flex justify-content-center align-content-between" onclick="post('@@todo/delete/{{$todo['id']}}@@')"><span class="material-icons">delete</span></button>
            </div>
          </td>
        </tr>
[[ endforeach; ]]
      </tbody>
    </table>
  </div>
</div>

<div class="row">
  <div class="col-lg-12">
    <h2>Past To Do:</h2>
    <table class="table table-striped" frame="box">
      <thead class="thead-dark">
        <tr>
          <th>Description</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
[[ foreach ($dones as $todo) : ]]
        <tr>
          <td class="align-middle"><?php echo "{$todo['description']}" ?></td>
          <td>
            <div class="btn-toolbar align-middle float-right">
              <button class="btn btn-success d-flex justify-content-center align-content-between mr-1" onclick="post('@@todo/done/{{$todo['id']}}@@')"><span class="material-icons">block</span></button>
              <button class="btn btn-primary d-flex justify-content-center align-content-between mr-1" onclick="get('@@todo/edit/{{$todo['id']}}@@')"><span class="material-icons">mode_edit</span></button>
              <button class="btn btn-danger d-flex justify-content-center align-content-between" onclick="post('@@todo/delete/{{$todo['id']}}@@')"><span class="material-icons">delete</span></button>
            </div>
          </td>
        </tr>
[[ endforeach; ]]
      </tbody>
    </table>

[[ endif; ]]

  </div>
</div>
          
%% views/footer.html %% 