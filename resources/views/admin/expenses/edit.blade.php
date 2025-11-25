 @extends('admin.layout.app')

 @section('page', 'Create Product')


 @section('contant')








     @if ($errors->any())
         <div class="alert alert-danger">
             <ul>
                 @foreach ($errors->all() as $error)
                     <li>{{ $error }}</li>
                 @endforeach
             </ul>
         </div>
     @endif




     {{-- @dd($errors) --}}
     <!-- Content wrapper -->
     <div class="content-wrapper">

         <!-- Content -->

         <div class="container-xxl flex-grow-1 container-p-y">




             <form action="{{ route('expenses.update', $expenses->id) }}" method="post" enctype="multipart/form-data">
                 @csrf
                 @method('PUT')
                 <div class="app-ecommerce">

                     <!-- Add Product -->
                     <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">




                     </div>

                     <div class="row">

                         <!-- First column-->
                         <div class="col-12 col-lg-12">
                             <!-- Product Information -->
                             <div class="card mb-12">
                                 <div class="card-header">
                                     <h5 class="card-tile mb-0">{!! __('admin.Edit Expenses') !!} </h5>
                                 </div>
                                 <div class="card-body">



                                     {{-- --------------------------------------------------------------  Note-------------------------------------------------------------------- --}}
                                     <div>
                                         <label class="form-label">{!! __('admin.Note') !!}</label>


                                         <textarea class=" form-control" name="note" placeholder="اكتب هنا ">{{ $expenses->note }}</textarea>




                                         @error('note')
                                             <br>
                                             <div class="alert alert-danger">{{ $message }}</div>
                                         @enderror



                                     </div>

                                     {{-- --------------------------------------------------------------end Note-------------------------------------------------------------------- --}}
                                     {{-- -------------------------------------------------------------- price-------------------------------------------------------------------- --}}
                                     <div class="mb-3">
                                         <br>
                                         <label class="form-label">{!! __('admin.Price') !!}</label>
                                         <input type="number" class="form-control" value="{{ $expenses->price }}"
                                             name="price" required step="0.01">





                                         @error('price')
                                             <br>
                                             <div class="alert alert-danger">{{ $message }}</div>
                                         @enderror



                                     </div>

                                     {{-- --------------------------------------------------------------end price-------------------------------------------------------------------- --}}



                                     {{-- -------------------------------------------------------------- photos-------------------------------------------------------------------- --}}




                                     <div>




                                         {{-- --------------------------------------------------------------end photos-------------------------------------------------------------------- --}}

                                         <button type="submit" class="btn btn-primary">{!! __('admin.Submit') !!} </button>
                                     </div>
                                 </div>





                             </div>


             </form>
         </div>



         <!-- /Organize Card -->
     </div>
     <!-- /Second column -->
     </div>
     </div>
     </div>
     <!-- / Content -->
     </form>



 @endsection

 @section('footer')
     <script src="{{ asset('admin') }}/js/app-ecommerce-product-add.js"></script>


 @endsection
