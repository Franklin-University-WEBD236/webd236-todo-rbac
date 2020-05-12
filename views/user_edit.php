%% views/header.html %%

<div class="row">
  <div class="col-lg-12">

    <form action="{{$action}}" method="post">
      <div class="form-group">
        <div class="row">
          <div class="col">
            <label for="firstName">First name</label>
            <input type="text" min="1" id="firstName" name="form[firstName]" class="form-control" placeholder="Enter first name" value="{{$this->value($form['firstName'])}}" />
          </div>
          <div class="col">
            <label for="lastName">Last name</label>
            <input type="text" min="1" id="lastName" name="form[lastName]" class="form-control" placeholder="Enter last name" value="{{$this->value($form['lastName'])}}" />
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="row">
          <div class="col">
            <label for="email">Email address</label>
            <input type="text" min="1" id="email1" name="form[email1]" class="form-control" placeholder="Enter email address" value="{{$this->value($form['email1'])}}" />
          </div>
          <div class="col">
            <label for="email">Verify email address</label>
            <input type="text" min="1" id="email2" name="form[email2]" class="form-control" placeholder="Re-enter email address" value="{{$this->value($form['email2'])}}" />
          </div>
        </div>
      </div>
      <div class="form-group mt-4">
        <button type="submit" class="btn btn-primary">Submit</button>
        <button class="btn btn-secondary" onclick="get('@@index@@')">Cancel</button>
      </div>
    </form>
  </div>
</div>

%% views/footer.html %%
