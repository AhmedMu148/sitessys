<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\SiteImgMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class MediaController extends Controller
{
    /**
     * Display media gallery
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active site found.'
                ], 404);
            }
            return redirect()->route('admin.dashboard')
                ->with('error', 'No active site found.');
        }

        $query = SiteImgMedia::where('site_id', $site->id);
        
        // Filter by type if specified
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $media = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Return JSON for API requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $media
            ]);
        }
        
        return view('admin.media.index', compact('media', 'site'));
    }

    /**
     * Show the upload form
     */
    public function showUploadForm()
    {
        return view('admin.media.upload', [
            'title' => 'Upload Media',
            'breadcrumb' => [
                ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['title' => 'Media', 'url' => route('admin.media.index')],
                ['title' => 'Upload', 'url' => '']
            ]
        ]);
    }

    /**
     * Handle file upload with drag-and-drop support
     */
    public function upload(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|mimes:jpeg,png,jpg,gif,svg,webp|max:5120', // 5MB max
        ]);

        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $uploadedFiles = [];

        foreach ($request->file('files') as $file) {
            try {
                // Store original file
                $path = $file->store('media/' . $site->id, 'public');
                $fullPath = Storage::disk('public')->path($path);
                
                // Generate thumbnail
                $thumbnailPath = $this->generateThumbnail($fullPath, $path, $site->id);
                
                // Create media record
                $media = SiteImgMedia::create([
                    'site_id' => $site->id,
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'thumbnail_path' => $thumbnailPath,
                    'type' => $this->getMediaType($file->getClientMimeType()),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getClientMimeType(),
                    'width' => null,
                    'height' => null,
                ]);

                // Get image dimensions
                if (str_starts_with($file->getClientMimeType(), 'image/')) {
                    $imageSize = getimagesize($fullPath);
                    if ($imageSize) {
                        $media->update([
                            'width' => $imageSize[0],
                            'height' => $imageSize[1],
                        ]);
                    }
                }

                $uploadedFiles[] = [
                    'id' => $media->id,
                    'name' => $media->name,
                    'path' => Storage::url($media->path),
                    'thumbnail' => $media->thumbnail_path ? Storage::url($media->thumbnail_path) : null,
                    'size' => $this->formatFileSize($media->size),
                    'dimensions' => $media->width && $media->height ? "{$media->width}x{$media->height}" : null,
                ];

            } catch (\Exception $e) {
                return response()->json(['error' => 'Failed to upload file: ' . $file->getClientOriginalName()], 500);
            }
        }

        return response()->json([
            'success' => true,
            'files' => $uploadedFiles,
            'message' => count($uploadedFiles) . ' file(s) uploaded successfully.'
        ]);
    }

    /**
     * Generate thumbnail for uploaded image
     */
    protected function generateThumbnail($originalPath, $originalStoragePath, $siteId)
    {
        try {
            $thumbnailDir = 'media/' . $siteId . '/thumbnails';
            $thumbnailName = 'thumb_' . basename($originalStoragePath);
            $thumbnailPath = $thumbnailDir . '/' . $thumbnailName;
            
            // Create thumbnail directory if it doesn't exist
            Storage::disk('public')->makeDirectory($thumbnailDir);
            
            // Generate thumbnail
            $thumbnail = Image::make($originalPath)
                ->fit(300, 300, function ($constraint) {
                    $constraint->upsize();
                })
                ->encode('jpg', 80);
            
            Storage::disk('public')->put($thumbnailPath, $thumbnail);
            
            return $thumbnailPath;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get media type from mime type
     */
    protected function getMediaType($mimeType)
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        } elseif (str_starts_with($mimeType, 'video/')) {
            return 'video';
        } elseif (str_starts_with($mimeType, 'audio/')) {
            return 'audio';
        } else {
            return 'document';
        }
    }

    /**
     * Format file size for display
     */
    protected function formatFileSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Delete media file
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $media = SiteImgMedia::where('id', $id)
            ->where('site_id', $site->id)
            ->firstOrFail();

        // Delete files from storage
        if (Storage::disk('public')->exists($media->path)) {
            Storage::disk('public')->delete($media->path);
        }
        
        if ($media->thumbnail_path && Storage::disk('public')->exists($media->thumbnail_path)) {
            Storage::disk('public')->delete($media->thumbnail_path);
        }

        // Delete database record
        $media->delete();

        return response()->json([
            'success' => true,
            'message' => 'Media file deleted successfully.'
        ]);
    }

    /**
     * Get media file details
     */
    public function show($id)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $media = SiteImgMedia::where('id', $id)
            ->where('site_id', $site->id)
            ->firstOrFail();

        return response()->json([
            'id' => $media->id,
            'name' => $media->name,
            'path' => Storage::url($media->path),
            'thumbnail' => $media->thumbnail_path ? Storage::url($media->thumbnail_path) : null,
            'size' => $this->formatFileSize($media->size),
            'dimensions' => $media->width && $media->height ? "{$media->width}x{$media->height}" : null,
            'type' => $media->type,
            'mime_type' => $media->mime_type,
            'created_at' => $media->created_at->format('M d, Y H:i'),
        ]);
    }

    /**
     * Regenerate thumbnail for media file
     */
    public function regenerateThumbnail($id)
    {
        $user = Auth::user();
        $site = $user->sites()->where('status_id', true)->first();
        
        if (!$site) {
            return response()->json(['error' => 'No active site found.'], 404);
        }

        $media = SiteImgMedia::where('id', $id)
            ->where('site_id', $site->id)
            ->where('type', 'image')
            ->firstOrFail();

        $fullPath = Storage::disk('public')->path($media->path);
        
        if (!file_exists($fullPath)) {
            return response()->json(['error' => 'Original file not found.'], 404);
        }

        $thumbnailPath = $this->generateThumbnail($fullPath, $media->path, $site->id);
        
        if ($thumbnailPath) {
            $media->update(['thumbnail_path' => $thumbnailPath]);
            
            return response()->json([
                'success' => true,
                'thumbnail' => Storage::url($thumbnailPath),
                'message' => 'Thumbnail regenerated successfully.'
            ]);
        }

        return response()->json(['error' => 'Failed to regenerate thumbnail.'], 500);
    }
}
