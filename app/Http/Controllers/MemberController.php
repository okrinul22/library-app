<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Book;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display the reading page for a specific content/chapter.
     */
    public function read($contentId)
    {
        $content = Content::with('book')->findOrFail($contentId);

        // Get all chapters for this book
        $chapters = Content::where('book_id', $content->book_id)
            ->where('status', 'published')
            ->orderBy('chapter')
            ->get();

        // Find current chapter index
        $currentChapterIndex = $chapters->search(function ($item) use ($contentId) {
            return $item->id == $contentId;
        });

        // Get previous and next chapters
        $previousChapter = $currentChapterIndex > 0 ? $chapters[$currentChapterIndex - 1] : null;
        $nextChapter = $currentChapterIndex < $chapters->count() - 1 ? $chapters[$currentChapterIndex + 1] : null;

        return view('member.read', compact('content', 'chapters', 'previousChapter', 'nextChapter', 'currentChapterIndex'));
    }

    /**
     * Display the member library page.
     */
    public function library()
    {
        return view('member.library');
    }

    /**
     * Display the member history page.
     */
    public function history()
    {
        return view('member.history');
    }

    /**
     * Display the member profile page.
     */
    public function profile()
    {
        return view('member.profile');
    }
}
