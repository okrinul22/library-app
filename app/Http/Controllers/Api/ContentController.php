<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Content;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    /**
     * Display a listing of contents.
     */
    public function index(Request $request)
    {
        $query = Content::query();

        // Filter by book
        if ($request->has('book_id')) {
            $query->where('book_id', $request->book_id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $contents = $query->with('book')->orderBy('created_at', 'desc')->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $contents
        ]);
    }

    /**
     * Store a newly created content.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'chapter' => 'nullable|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $content = Content::create([
            'book_id' => $request->book_id,
            'title' => $request->title,
            'content' => $request->content,
            'chapter' => $request->chapter ?? 1,
            'status' => 'approved'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Konten berhasil dibuat',
            'data' => $content->load('book')
        ], 201);
    }

    /**
     * Display the specified content.
     */
    public function show($id)
    {
        $content = Content::with('book')->find($id);

        if (!$content) {
            return response()->json([
                'success' => false,
                'message' => 'Konten tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $content
        ]);
    }

    /**
     * Update the specified content.
     */
    public function update(Request $request, $id)
    {
        $content = Content::find($id);

        if (!$content) {
            return response()->json([
                'success' => false,
                'message' => 'Konten tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'chapter' => 'nullable|integer|min:1',
            'status' => 'sometimes|required|in:pending,approved,rejected'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $data = $request->only(['title', 'content', 'chapter', 'status']);
        $content->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Konten berhasil diupdate',
            'data' => $content->fresh()->load('book')
        ]);
    }

    /**
     * Remove the specified content.
     */
    public function destroy($id)
    {
        $content = Content::find($id);

        if (!$content) {
            return response()->json([
                'success' => false,
                'message' => 'Konten tidak ditemukan'
            ], 404);
        }

        // Delete word file if exists
        if ($content->file_path) {
            Storage::disk('public')->delete($content->file_path);
        }

        $content->delete();

        return response()->json([
            'success' => true,
            'message' => 'Konten berhasil dihapus'
        ]);
    }

    /**
     * Upload Word file from writer
     */
    public function uploadWordFile(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:doc,docx|max:10240'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $content = Content::find($id);

        if (!$content) {
            return response()->json([
                'success' => false,
                'message' => 'Konten tidak ditemukan'
            ], 404);
        }

        // Delete old file if exists
        if ($content->file_path) {
            Storage::disk('public')->delete($content->file_path);
        }

        // Store new file
        $path = $request->file('file')->store('content-files', 'public');
        $content->update([
            'file_path' => $path,
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'File berhasil diupload',
            'data' => $content->fresh()
        ]);
    }

    /**
     * Preview content
     */
    public function preview($id)
    {
        $content = Content::with('book')->find($id);

        if (!$content) {
            return response()->json([
                'success' => false,
                'message' => 'Konten tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $content->id,
                'title' => $content->title,
                'content' => $content->content,
                'book' => $content->book
            ]
        ]);
    }

    /**
     * Link content to book
     */
    public function linkToBook(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $content = Content::find($id);

        if (!$content) {
            return response()->json([
                'success' => false,
                'message' => 'Konten tidak ditemukan'
            ], 404);
        }

        $content->update(['book_id' => $request->book_id]);

        return response()->json([
            'success' => true,
            'message' => 'Konten berhasil dihubungkan ke buku',
            'data' => $content->fresh()->load('book')
        ]);
    }

    /**
     * Writer upload content
     */
    public function writerUpload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'book_id' => 'nullable|exists:books,id',
            'file' => 'required|file|mimes:doc,docx|max:10240'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $path = $request->file('file')->store('writer-uploads', 'public');

        $content = Content::create([
            'title' => $request->title,
            'book_id' => $request->book_id,
            'file_path' => $path,
            'status' => 'pending',
            'uploaded_by' => $request->user()->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Konten berhasil diupload',
            'data' => $content
        ], 201);
    }

    /**
     * Writer contents - get contents uploaded by writer
     */
    public function writerContents(Request $request)
    {
        $contents = Content::where('uploaded_by', $request->user()->id)
            ->with('book')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $contents
        ]);
    }
}
