{{ Form::open(['id' => 'contact', 'url' => '/contact', 'class' => 'max-w-2xl mx-auto']) }}
    <p class="hidden">
        <input type="text" name="email"/>
        <input type="text" name="website" />
        <input type="text" name="url" />
    </p>
    <div class="md:flex md:-mx-2">
        <div class="w-full md:w-1/2 md:px-2">
            <div class="relative mb-3">
                <label for="" class="block tracking-wide text-sm font-medium mb-1 text-gray-700"><span>Name</span> <span class="text-red-500">*</span></label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    class="appearance-none block text-gray-900 border rounded-lg py-3 placeholder-gray-500 placeholder-opacity-75 leading-tight hover:border-blue-500 focus:outline-none focus:border-blue-500 border-gray-400 px-4 w-full"
                    autofocus
                />
                @if ($errors->has('name'))
                    <p class="text-red-500 text-xs italic mt-1">{{ $errors->first('name') }}</p>
                @endif
            </div>
        </div>
        <div class="w-full md:w-1/2 md:px-2">
            <div class="relative mb-3">
                <label for="" class="block tracking-wide text-sm font-medium mb-1 text-gray-700">
                    <span>Company</span>
                </label>
                <input
                    id="company"
                    name="company"
                    type="text"
                    class="appearance-none block text-gray-900 border rounded-lg py-3 placeholder-gray-500 placeholder-opacity-75 leading-tight hover:border-blue-500 focus:outline-none focus:border-blue-500 border-gray-400 px-4 w-full"
                />
            </div>
        </div>
    </div>
    <div class="md:flex md:-mx-2">
        <div class="w-full md:w-1/2 md:px-2">
            <div class="relative mb-3">
                <label for="" class="block tracking-wide text-sm font-medium mb-1 text-gray-700"><span>Email</span> <span class="text-red-500">*</span></label>
                <input
                    id="cemail"
                    name="cemail"
                    type="email"
                    class="appearance-none block text-gray-900 border rounded-lg py-3 placeholder-gray-500 placeholder-opacity-75 leading-tight hover:border-blue-500 focus:outline-none focus:border-blue-500 border-gray-400 px-4 w-full"
                />
            </div>
            @if ($errors->has('cemail'))
                <p class="text-red-500 text-xs italic mt-1">{{ $errors->first('cemail') }}</p>
            @endif
        </div>
        <div class="w-full md:w-1/2 md:px-2">
            <div class="relative mb-3">
                <label for="" class="block tracking-wide text-sm font-medium mb-1 text-gray-700">
                    <span>Phone</span>
                </label>
                <input
                    id="phone"
                    name="phone"
                    type="text"
                    class="appearance-none block text-gray-900 border rounded-lg py-3 placeholder-gray-500 placeholder-opacity-75 leading-tight hover:border-blue-500 focus:outline-none focus:border-blue-500 border-gray-400 px-4 w-full"
                />
            </div>
        </div>
    </div>
    <div class="w-full">
        <div class="relative mb-3">
            <label for="" class="block tracking-wide text-sm font-medium mb-1 text-gray-700"><span>Message</span> <span class="text-red-500">*</span></label>
            <textarea
                id="message"
                name="message"
                rows="5"
                class="appearance-none block w-full text-gray-900 border rounded-lg py-3 px-4 placeholder-gray-500 placeholder-opacity-75 leading-tight focus:outline-none focus:border-blue-500 border-gray-400"
            ></textarea>
            @if ($errors->has('message'))
                <p class="text-red-500 text-xs italic mt-1">{{ $errors->first('message') }}</p>
            @endif
        </div>
    </div>
    <div class="flex justify-center sm:justify-end mt-3">
        <button type="submit" class="px-5 py-2.5 bg-blue-500 text-white tracking-wider rounded hover:no-underline hover:bg-blue-400 hover:text-white active:bg-blue-400 active:text-white focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500">Submit</button>
    </div>
{{ Form::close() }}