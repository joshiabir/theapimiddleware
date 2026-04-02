<div class='w-full rounded-xl'>
   <div class='p-6'>
    <h4 class='text-2xl font-semibold'>License Information</h4>
   </div>
   <div class='p-6 pt-0 grid grid-cols-2'>
        <div class='p-3'>
                <h5 class='text-lg font-semibold'>Registered Email</h5>
                <p class='text-sm'>{{$email}}</p>
        </div>
        <div class='p-3'>
                <h5 class='text-lg font-semibold'>Allowed Domains</h5>
                <p>{{$domains}}</p>
                <p class='text-sm'><b>{{$existing}} Domains active </b></p>
        </div>
        <div class='p-3'>
                <h5 class='text-lg font-semibold'>Global per User Rate Limit</h5>
                <p class='text-sm'>{{$rpm}} Requests Per Minute</p>
        </div>
        <div class='p-3'>
                <h5 class='text-lg font-semibold'>Subscription Active Till</h5>
                <p class='text-sm'>{{$end_date}}</p>
        </div>
   </div>
</div>
