<x-app-layout>

    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">{{ $error }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endforeach
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="container">
                <div class="row">
                <div class="col-md-8">
                <div class="card p-2">
                    <form action="{{ url('/edit_category_confirm', $category->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <h3>Edit {{ $category->cat_name }}</h3>
                            <label for="cat_name">Category Name</label>
                            <input type="text" class="form-control" name="cat_name"
                                placeholder="Enter category name" value="{{$category->cat_name}}">
                        </div>
                        <div class="mb-3">
                            <label for="user_id">User ID</label>
                            <input type="number" class="form-control" name="user_id" placeholder="Enter User ID" value="{{ Auth::user()->id }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="image">Image</label>
                            <input type="file" class="form-control" name="image">
                        </div>
                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                        <a href="{{route('AllCat')}}" class="btn btn-secondary">Back</a>
                    </form>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-3">
                    <label for="image">Current Image</label>
                    <img src="/storage/category/{{ $category->image }}" alt="image">
                </div>
            </div>
            

        </div>
    </div>
        </div>
</x-app-layout>
