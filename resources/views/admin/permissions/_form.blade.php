@csrf

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong>
        There were some problems with your input.
        <br />
        <br />
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="margin-bottom: 1rem">
    <label
        for="slug"
        style="display: block"
    >
        Permission Slug
    </label>
    <input
        type="text"
        name="slug"
        id="slug"
        value="{{ old('slug', $permission->slug ?? '') }}"
        style="width: 100%; padding: 0.5rem"
        required
    />
    <small>e.g., 'posts.create', 'users.delete'. Must be unique and contain no spaces.</small>
</div>

<div style="margin-bottom: 1rem">
    <label
        for="description"
        style="display: block"
    >
        Description
    </label>
    <textarea
        name="description"
        id="description"
        rows="3"
        style="width: 100%; padding: 0.5rem"
    >
{{ old('description', $permission->description ?? '') }}</textarea
    >
    <small>A brief explanation of what this permission allows.</small>
</div>

<button
    type="submit"
    class="btn btn-primary"
>
    {{ $submitButtonText ?? 'Save' }}
</button>
