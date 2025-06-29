@extends('layouts.admin')
@section('content')
@can('product_add')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>Add New Product</h3>
            <a href="{{route('product.view')}}" title="Back to list"><i  class="fas fa-arrow-circle-left fa-2xl" style="color: blue;"></i></a>
        </div>
        <div class="card-body">
            <form action="{{route('product.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control">
                            @error('name')
                                <strong class="text text-danger">{{$message}}</strong>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" name="price" class="form-control">
                            @error('price')
                                <strong class="text text-danger">{{$message}}</strong>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <label for="discount" class="form-label">Discount %</label>
                            <input type="number" name="discount" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select name="category" class="form-control category" id="category">
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <strong class="text text-danger">{{$message}}</strong>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <label for="subcategory" class="form-label">Subcategory</label>
                            <select name="subcategory" class="form-control" id="subcategory">
                                <option>Select Subcategory</option>
                                @foreach ($subcategories as $subcategory)
                                    <option value="{{$subcategory->id}}">{{$subcategory->name}}</option>
                                @endforeach
                            </select>
                            @error('subcategory')
                                <strong class="text text-danger">{{$message}}</strong>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="tag" class="form-label">Tags</label>
                            <select id="select-tags" name="tags[]" class="demo-default" multiple placeholder="Select Tags">
                                <option value="">Select Tags</option>
                                <optgroup label="tags">
                                    @foreach ($tags as $tag)
                                        <option value="{{$tag->id}}">{{$tag->name}}</option>
                                    @endforeach
                                </optgroup>
                              </select>
                        </div>
                        <div class="col-lg-6">
                            <label for="brand" class="form-label">Brand</label>
                            <select name="brand" class="form-control">
                              <option value="">Select Brand</option>
                              @foreach ($brands as $brand)
                                <option value="{{$brand->id}}">{{$brand->name}}</option>
                              @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label for="desp" class="form-label">Description</label>
                            <textarea name="desp" id="summernote" class="form-control" rows="3"></textarea>
                            @error('desp')
                                <strong class="text text-danger">{{$message}}</strong>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-6">
                        <label for="image" class="form-label">Product Image</label>
                        <input type="file" class="form-control" name="image" id="prod_img">
                        <div id="img_prev" class="pt-2 img_prev"></div>
                        @error('image')
                            <strong class="text text-danger">{{$message}}</strong>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        <label for="gal_image" class="form-label">Gallery Image</label>
                        <input type="file" class="form-control" name="gal_image[]" id="gal_image" multiple accept="image/*">
                        <div class="pt-2 images_preview" id="images_preview"></div>
                    </div>
                </div>
                <div class="col-lg-6 m-auto">
                    <div class="mb-3 ">
                        <button type="submit" class="btn btn-primary form-control">Add</button>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
@else
<h3>You do not have permission to view this page</h3>
@endcan
@endsection
@section('jscript')
<script>
    $('#select-tags').selectize({ sortField: 'text' });
</script>
<script>
    $(document).ready(function() {
        $('#summernote').summernote();
    });
</script>
<script>
    document.getElementById('prod_img').addEventListener('change', function(event){
        let img_prev = document.getElementById('img_prev');
        img_prev.innerHTML = '';
        let file = event.target.files;
        console.log(file);
        
        if(file.length > 0){
            file = file[0];
            let reader = new FileReader();
            reader.onload = function(e){
                let img = document.createElement('img');
                img.src = e.target.result;
                img.setAttribute('width', '100')
                img_prev.appendChild(img)
            }
            reader.readAsDataURL(file);
        }
        
    })
</script>
<script>
    document.getElementById('gal_image').addEventListener('change', function(event){
        const images_preview = document.getElementById('images_preview');
        images_preview.innerHTML  = '';
        let files = event.target.files;
        
        if(files.length > 0){
            for(let i = 0; i < files.length; i++){
                let file = files[i];
                if(file.type.startsWith('image/')){
                    let reader = new FileReader();
                    reader.onload = function(e){
                        let img = document.createElement('img');
                        img.src = e.target.result;
                        img.width = 120;
                        images_preview.appendChild(img);
                        
                    }
                    reader.readAsDataURL(file);
                }
            }
        }
    })
</script>
<script> 
    let category = document.querySelector('#category');
    let subcategory = document.querySelector('#subcategory');

    category.onchange = function(){
        let category_id = $(this).children('option:selected').val();

        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        $.ajax({
            url:'/getSubcategory',
            type:'POST',
            data:{'category_id':category_id},
            success: function(data){
                subcategory.innerHTML = data;
            }
        })
    }   
</script>
    @if (session('success'))
        <script>
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "{{session('success')}}",
                showConfirmButton: false,
                timer: 1500
                });
        </script>
    @endif
@endsection