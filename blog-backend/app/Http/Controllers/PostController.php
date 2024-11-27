<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('index');
    }

    public function index()
    {
        $posts = Post::with('user')->latest()->paginate(5);

        $posts->getCollection()->each(function ($post) {
            if ($post->image) {
                // Assuming images are stored under /storage/ path
                $post->image_url = asset('storage/' . $post->image);
            }
        });

        // Return the posts with the image URLs
        return response()->json($posts);
    }

    public function userPosts()
    {
        $posts = Post::where('user_id', Auth::id())->paginate(10);

        $posts->getCollection()->each(function ($post) {
            if ($post->image) {
                // Assuming images are stored under /storage/ path
                $post->image_url = asset('storage/' . $post->image);
            }
        });

        return response()->json($posts);
    }

    public function user()
    {
        $posts = Post::with('user')->latest()->paginate(5);

        $posts->getCollection()->each(function ($post) {
            if ($post->image) {
                // Assuming images are stored under /storage/ path
                $post->image_url = asset('storage/' . $post->image);
            }
        });

        // Return the posts with the image URLs
        return response()->json($posts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:1024', // Validate the image if uploaded
        ]);

        // Handle file upload if there's an image
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/images');
        }

        $post = new Post();
        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->user_id = Auth::id(); // Set the user_id to the currently authenticated user
        $post->image = isset($imagePath) ? basename($imagePath) : null; // Save image name if uploaded
        $post->save();

        return response()->json($post, 201);
    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:1024',
        ]);

        // Handle file upload if there's an image
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($post->image && Storage::exists('public/' . $post->image)) {
                Storage::delete('public/' . $post->image);
            }

            $imagePath = $request->file('image')->store('public');
            $post->image = basename($imagePath); // Save the new image name
        }

        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->save();

        return response()->json($post);
    }

    public function destroy($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        // Delete the image if it exists
        if ($post->image && Storage::exists('public/' . $post->image)) {
            Storage::delete('public/' . $post->image);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }
}
