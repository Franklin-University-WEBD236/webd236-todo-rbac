%% views/header.html %%

<div class="row">
  <div class="col-lg-12">

    <form action="@@user/login@@" method="post">
      <div class="form-group">
        <label for="email">Email address</label>
        <input type="text" min="1" id="email" name="form[email]" class="form-control" placeholder="Enter email address" value="{{$this->value($form['email'])}}" />
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" min="1" id="password" name="form[password]" class="form-control" placeholder="Enter password" value="{{$this->value($form['password'])}}" />
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
        <button class="btn btn-secondary" onclick="return get('@@index@@')">Cancel</button>
      </div>
    </form>
    New here? You can <a href="@@user/register@@">create an account</a>.
  </div>
</div>

%% views/footer.html %%
