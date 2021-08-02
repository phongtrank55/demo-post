@php
    $selected_array = explode(',',$selected_ids);
    if(config('app.storage_type') == 'objectstorage') {
        $media_domain = config('filesystems.disks.sop.container_url');
    }elseif(config('app.storage_type') == 'digitalocean') {
        $media_domain = config('filesystems.disks.do.domain').'/'.config('filesystems.disks.do.folder');
    }elseif(config('app.storage_type') == 'linode') {
        $media_domain = config('filesystems.disks.linode.domain').'/'.config('filesystems.disks.linode.folder');
    }elseif(config('app.storage_type') == 'vhost') {
        $media_domain = config('filesystems.disks.vhost.domain').'/'.config('filesystems.disks.vhost.folder');
    }else {
        $media_domain = url('uploads');
    }
@endphp
@foreach($data as $media)
    <div id="media-item-{{$media->id}}" class="media-item @if(in_array($media->id,$selected_array)) {{'active'}} @endif"
         data-id="{{$media->id}}"
         data-user="{{$media->user_id}}"
         data-name="{{$media->name}}"
         data-title="{{$media->title}}"
         data-caption="{{$media->caption}}"
         data-time="{{date('H:i:s d-m-Y',strtotime($media->created_at))}}">
        <img src="{{$media_domain}}/{{date('Y',strtotime($media->created_at))}}/{{date('m',strtotime($media->created_at))}}/{{$media->name}}" />
    </div>
@endforeach