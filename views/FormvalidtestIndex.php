%% views/header.html %%

<div class="row">
  <div class="col-lg-12">
    <h1>{{$title}}</h1>
    
    <form action="@@formvalidtest/index@@" method="post">
      <div class="form-group">
        <label for="required">A required field</label>
        <input type="text" min="1" id="required" name="form[required]" class="form-control" placeholder="Enter something" value="{{$this->value($form['required'])}}" />
      </div>
      <div class="form-group">
        <label for="phone">A phone number</label>
        <input type="text" min="1" id="phone" name="form[phone]" class="form-control" placeholder="Enter something" value="{{$this->value($form['phone'])}}" />
      </div>
      <div class="form-group">
        <label for="email">An email address</label>
        <input type="text" min="1" id="email" name="form[email]" class="form-control" placeholder="Enter something" value="{{$this->value($form['email'])}}" />
      </div>
      <div class="form-group">
        <label for="integer">An integer</label>
        <input type="text" min="1" id="integer" name="form[integer]" class="form-control" placeholder="Enter something" value="{{$this->value($form['integer'])}}" />
      </div>
      <div class="form-group">
        <label for="float">A floating point number</label>
        <input type="text" min="1" id="float" name="form[float]" class="form-control" placeholder="Enter something" value="{{$this->value($form['float'])}}" />
      </div>
      <div class="form-group">
        <label for="money">An amount of money</label>
        <input type="text" min="1" id="money" name="form[money]" class="form-control" placeholder="Enter something" value="{{$this->value($form['money'])}}" />
      </div>
      <div class="form-group">
        <label for="password">A strong password</label>
        <input type="text" min="1" id="password" name="form[password]" class="form-control" placeholder="Enter something" value="{{$this->value($form['password'])}}" />
      </div>
      <div class="form-group">
        <label for="password2">Must match above password</label>
        <input type="text" min="1" id="password2" name="form[password2]" class="form-control" placeholder="Enter something" value="{{$this->value($form['password2'])}}" />
      </div>
      <div class="form-group">
        <label for="between">Must be between 25 and 555</label>
        <input type="text" min="1" id="password2" name="form[between]" class="form-control" placeholder="Enter something" value="{{$this->value($form['between'])}}" />
      </div>
      <div class="form-group mt-4">
        <button type="submit" class="btn btn-primary">Submit</button>
        <button class="btn btn-secondary" onclick="get('@@index@@')">Cancel</button>
      </div>
    </form>

  </div>
</div>
          
%% views/footer.html %% 