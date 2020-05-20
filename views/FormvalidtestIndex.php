%% views/header.html %%

<div class="row">
  <div class="col-lg-12">
    <h1>{{$title}}</h1>
    
    <form action="@@formvalidtest/index@@" method="post">
      <div class="form-group">
        <label for="required">A required field</label>
        <input type="text" min="1" id="required" name="form[required]" class="form-control" placeholder="Enter something" value="{{$this->value($form['required'])}}" />
        <small class="text-danger">{{$this->value($errors['required'])}}</small>
      </div>
      <div class="form-group">
        <label for="phone">A phone number</label>
        <input type="text" min="1" id="phone" name="form[phone]" class="form-control" placeholder="Enter something" value="{{$this->value($form['phone'])}}" />
        <small class="text-danger">{{$this->value($errors['phone'])}}</small>
      </div>
      <div class="form-group">
        <label for="email">An email address</label>
        <input type="text" min="1" id="email" name="form[email]" class="form-control" placeholder="Enter something" value="{{$this->value($form['email'])}}" />
        <small class="text-danger">{{$this->value($errors['email'])}}</small>
      </div>
      <div class="form-group">
        <label for="integer">An integer</label>
        <input type="text" min="1" id="integer" name="form[integer]" class="form-control" placeholder="Enter something" value="{{$this->value($form['integer'])}}" />
        <small class="text-danger">{{$this->value($errors['integer'])}}</small>
      </div>
      <div class="form-group">
        <label for="float">A floating point number</label>
        <input type="text" min="1" id="float" name="form[float]" class="form-control" placeholder="Enter something" value="{{$this->value($form['float'])}}" />
        <small class="text-danger">{{$this->value($errors['float'])}}</small>
      </div>
      <div class="form-group">
        <label for="money">An amount of money</label>
        <input type="text" min="1" id="money" name="form[money]" class="form-control" placeholder="Enter something" value="{{$this->value($form['money'])}}" />
        <small class="text-danger">{{$this->value($errors['money'])}}</small>
      </div>
      <div class="form-group">
        <label for="password">A strong password</label>
        <input type="text" min="1" id="password" name="form[password]" class="form-control" placeholder="Enter something" value="{{$this->value($form['password'])}}" />
        <small class="text-danger">{{$this->value($errors['password'])}}</small>
      </div>
      <div class="form-group">
        <label for="password2">Must match above password</label>
        <input type="text" min="1" id="password2" name="form[password2]" class="form-control" placeholder="Enter something" value="{{$this->value($form['password2'])}}" />
        <small class="text-danger">{{$this->value($errors['password2'])}}</small>
      </div>
      <div class="form-group">
        <label for="between" class="control-label">Must be between 25 and 555</label>
        <input type="text" min="1" id="between" name="form[between]" class="form-control" placeholder="Enter something" value="{{$this->value($form['between'])}}" />
        <small class="text-danger">{{$this->value($errors['between'])}}</small>
      </div>
      <div class="form-group mt-4">
        <button type="submit" class="btn btn-primary">Submit</button>
        <button class="btn btn-secondary" onclick="get('@@index@@')">Cancel</button>
      </div>
    </form>
  </div>
</div>
          
%% views/footer.html %% 