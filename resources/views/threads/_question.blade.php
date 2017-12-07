{{-- Editing the question --}}
<div class="panel panel-default" v-if="editing">
    <div class="panel-heading">
        <div class="level">
            <div class="form-group" style="width: 100%">
                <input type="text" class="form-control" v-model="title">
            </div>
        </div>
    </div>

    <div class="panel-body">
        <div class="form-group">
            <wysiwyg name="body" v-model="form.body" :value="form.body"></wysiwyg>
        </div>
    </div>

    <div class="panel-footer">
        <div class="level">
            @can('update', $thread)
                <button class="btn btn-xs btn-primary" @click="update"> Update</button>
                <button class="btn btn-xs btn-default" @click="cancel"><i class="glyphicon glyphicon-pencil"></i> Cancel</button>

                <form action="{{ $thread->path() }}" method="POST" class="ml-a">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button class="btn btn-link">Delete Thread</button>
                </form>
            @endcan
        </div>
    </div>
</div>

{{-- Viewing the question --}}
<div class="panel panel-default" v-else>
    <div class="panel-heading">
        <div class="level">
            <img src="{{ $thread->creator->avatar_path }}" width="25" height="25" class="mr-1" alt="{{ $thread->creator->name }}">
            <span class="flex">
                <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a> posted <span v-text="title"></span>
            </span>
        </div>
    </div>

    <div class="panel-body" v-html="body"></div>

    <div class="panel-footer" v-if="authorize('owns', thread)">
        <button class="btn btn-xs btn-default" @click="editing = true"><i class="glyphicon glyphicon-pencil"></i> Edit</button>
    </div>
</div>