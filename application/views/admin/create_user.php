<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<div class="container">
    <div class="panel panel-primary">
        <div class="panel-heading">
           <h4> Create User </h4>
        </div>
      <div class="panel-body">
      <form>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="firstName">First Name</label>
                <input type="text" class="form-control" id="first_name" placeholder="First Name">
            </div>
            <div class="form-group col-md-6">
                <label for="lastName">Last Name</label>
                <input type="text" class="form-control" id="last_name" placeholder="Last Name">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" placeholder="email">
            </div>
            <div class="form-group col-md-6">
                <label for="phoneNumber">Phone Number</label>
                <input type="text" class="form-control" id="phone" placeholder="Phone Number">
            </div>
        </div>
        <label for="gender">Gender</label> <br/>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" id="Male" value="1">
            <label class="form-check-label" for="inlineRadio1">Male</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" id="Female" value="2">
            <label class="form-check-label" for="inlineRadio2">Female</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" id="Other" value="3">
            <label class="form-check-label" for="inlineRadio3">Other</label>
        </div>
        <div class="form-group">
            <label for="note">Note</label>
            <textarea class="form-control" id="note" rows="2" placeholder="Enter note here..."></textarea>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="text" class="form-control" id="password" placeholder="password">
        </div>
        <button type="button" class="btn btn-primary">Submit</button>
        </form>
      </div>
    
</div>

