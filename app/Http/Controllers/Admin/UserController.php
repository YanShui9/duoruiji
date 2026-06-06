<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $query = User::query();

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15)->appends(request()->query());
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(UserRequest $request)
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => 1,
        ];

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('users', 'public');
            $this->createThumbnail($data['avatar']);
        }

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', '用户添加成功');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->only(['name', 'email']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
                $oldThumb = str_replace('/users/', '/users/thumb_', $user->avatar);
                Storage::disk('public')->delete($oldThumb);
            }
            $data['avatar'] = $request->file('avatar')->store('users', 'public');
            $this->createThumbnail($data['avatar']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', '用户更新成功');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', '不能删除当前登录用户');
        }

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $thumb = str_replace('/users/', '/users/thumb_', $user->avatar);
            Storage::disk('public')->delete($thumb);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', '用户删除成功');
    }

    private function createThumbnail($path)
    {
        $fullPath = storage_path('app/public/' . $path);
        $thumbPath = str_replace('/users/', '/users/thumb_', $path);

        \Image::make($fullPath)
            ->fit(200, 200)
            ->save(storage_path('app/public/' . $thumbPath));
    }
}
