 <!-- Category Details -->
<div class="tab-pane active" id="details" role="tabpanel" 
aria-expanded="true">

<div class="row mb-2">
    <div class="col-6">
        <h4 class="mt-0 text-left"> {{ @$company->name }} - Company Detail</h4>
    </div>
</div>

 <form   method="post" 
          action="{{ route('companies.update',$company->id) }}" enctype="multipart/form-data">
          <input type="hidden" name="_method" value="PUT">
              @csrf
                
                <!-- Current Password -->
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Name 
                                                </label>

                                                <input  name="name" value="{{ $company->name }}" type="text" class="form-control" placeholder="Name" required="">
                                            </div>
                                        </div>
                                    
                                       <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Address 1
                                                </label>
                                                <textarea  name="address"  type="text" class="form-control" placeholder="Property Address" >
                                                {{ $company->address_1 }}</textarea>
                                            </div>
                                        </div>

                                         <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Address 2
                                                </label>
                                                <textarea  name="address_2"  type="text" class="form-control" placeholder="Address 1" >
                                                 {{ $company->address_2 }}</textarea>
                                            </div>
                                        </div>
                                   
                                   
                                       <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">City 
                                                </label>
                                                <input  name="city" value="{{ $company->city }}" type="text" class="form-control" placeholder="City">
                                            </div>
                                        </div>
                                    
                                       <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">State
                                                </label>
                                                <input  name="state"  value="{{ $company->state }}" type="text" class="form-control" placeholder="State" >
                                            </div>
                                        </div>
                                    
                                       <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Country 
                                                </label>
                                                <input  name="country" value="{{ $company->country }}" type="text" class="form-control" placeholder="Country">
                                            </div>
                                        </div>
                                  
                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Zip Code 
                                                </label>
                                                <input  name="zip_code"  value="{{ $company->zip_code }}" type="text" class="form-control" placeholder="Zip Code" >
                                            </div>
                                        </div>

                                    
                                        
                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Photo 
                                                </label>
                                                <input  name="photo"  type="file">
                                            </div>
                                        </div>

                                       <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Notes 
                                                </label>
                                                <textarea  name="notes"  type="text" class="form-control" placeholder="Notes" >
                                                 {{ $company->notes }}</textarea>
                                            </div>
                                        </div>

                                         <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                @if(!empty($company->photo))
                                                <img style="width: 200px;" src="{{ url(\Storage::url($company->photo)) }}" />
                                                @endif

                                            </div>
                                        </div>
                                    
                                       
                                    </div>

                <!-- Submit Button -->
                <div class="col-12 text-center">
                    <button id="change-password-button" type="submit" class="btn btn-danger">Update Company
                    </button>
                </div>

            </form>
          </div>