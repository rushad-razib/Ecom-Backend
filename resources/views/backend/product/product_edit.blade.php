@extends('layouts.admin')
@section('content')
@can('product_edit')
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3>{{$product->name}} - Edit</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('product.update', $product->id)}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" name="name" class="form-control" value="{{$product->name}}">
                                        @error('name')
                                            <strong class="text text-danger">{{$message}}</strong>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="mb-3">
                                        <label for="discount" class="form-label">Discount %</label>
                                        <input type="number" name="discount" class="form-control" value="{{$product->discount}}">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="mb-3">
                                        <label for="category" class="form-label">Category</label>
                                        <select name="category" class="form-control category" id="category">
                                            @foreach ($categories as $category)
                                                <option value="{{$category->id}}" {{($category->id == $product->category_id?'selected':'')}}>{{$category->name}}</option>
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
                                            @foreach ($subcategories as $subcategory)
                                                <option value="{{$subcategory->id}}" {{($subcategory->id == $product->subcategory_id?'selected':'')}}>{{$subcategory->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('subcategory')
                                            <strong class="text text-danger">{{$message}}</strong>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3">
                                    <label for="tag" class="form-label">Tags</label>
                                    <select id="select-edit-tags" name="tags[]" class="demo-default" multiple placeholder="Select Tags">
                                        <option value="">Select Tags</option>
                                        <optgroup label="tags">
                                            @foreach ($tags as $tag)
                                                <option value="{{$tag->id}}">{{$tag->name}}</option>
                                            @endforeach
                                        </optgroup>
                                      </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="desp" class="form-label">Description</label>
                                        <textarea name="desp" id="summernote" class="form-control" rows="3">{!!$product->description!!}</textarea>
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
                                    <img height="70" src="{{asset('uploads/product')}}/{{$product->image}}" alt="">
                                    @error('image')
                                        <strong class="text text-danger">{{$message}}</strong>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label for="gal_image" class="form-label">Gallery Image</label>
                                    <input type="file" class="form-control" name="gal_image[]" id="gal_image" multiple accept="image/*">
                                    <div class="my-3">
                                        <div class="avail_gal_img">
                                            @foreach (\App\Models\Gallery::where('product_id', $product->id)->get() as $gal_img)
                                                <div style="position: relative!important; display:inline-block">
                                                    <img height='70' src="{{asset('uploads/gallery')}}/{{$gal_img->image}}" alt="">
                                                    <i data-id = '{{$gal_img->id}}' data-product = '{{$gal_img->product_id}}' class="fas fa-times-circle removeImage" style="color:red; position:absolute!important; top:5px; right:5px; font-size:25px; opacity:0.7; z-index:10"></i>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="pt-2 images_preview" id="images_preview"></div>
                                </div>
                            </div>
                            <div class="col-lg-6 m-auto">
                                <div class="mb-3 ">
                                    <button type="submit" class="btn btn-primary form-control">Update</button>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
<h3>You do not have permission to view this page</h3>
@endcan
@endsection
@section('jscript')
    <script>
        $('#select-edit-tags').selectize({ sortField: 'text' });
    </script>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote();
        });
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
                            let wrapper = document.createElement('div');
                            wrapper.style.position = 'relative';
                            wrapper.style.display = 'inline-block';

                            let img = document.createElement('img');
                            img.src = e.target.result;
                            img.width = '120';

                            let cross = document.createElement('i');
                            cross.setAttribute('class', 'fas fa-times-circle');
                            cross.style.position = 'absolute';
                            cross.style.top = '5px';
                            cross.style.right = '5px';
                            cross.style.fontSize = '25px';
                            cross.style.color = 'red';
                            cross.style.opacity = '0.7';
                            
                            cross.addEventListener('click', function(){
                                wrapper.remove();
                            })
                            wrapper.appendChild(img);
                            wrapper.appendChild(cross);
                            images_preview.appendChild(wrapper);

                        }
                        reader.readAsDataURL(file);
                    }
                }
            }
        })
    </script>
    <script>
        $('.removeImage').click(function(){
            let image_id = $(this).attr('data-id');
            let product_id = $(this).attr('data-product');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:'/getGalImage',
                type:'POST',
                data:{'image_id':image_id, 'product_id':product_id},
                success: function(data){
                    alert(data)
                }
            })
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