<?php
if (!function_exists('view_safe_image_path')) {
    function view_safe_image_path($image)
    {
        $image = trim((string) $image);
        if ($image === '') {
            return '../images/placeholder.png';
        }
        if (preg_match('#^(https?:)?//#i', $image) || strpos($image, 'data:image/') === 0) {
            return $image;
        }
        $normalized = str_replace('\\', '/', $image);
        $fileName = basename($normalized);

        // If the string is a real web-root-relative path and the file exists,
        // keep it. Otherwise fall back to the repository images folder.
        if (strpos($normalized, '/') === 0) {
            $webRootPath = rtrim((string) ($_SERVER['DOCUMENT_ROOT'] ?? ''), '/\\') . $normalized;
            if ($webRootPath !== '' && file_exists($webRootPath)) {
                return $normalized;
            }
        }

        // Normalize the candidate relative paths and prefer the repository's
        // /mvc/view/images location (which public pages reach via "../images/").
        $candidateInView = __DIR__ . '/../' . ltrim($normalized, './');
        if (file_exists($candidateInView)) {
            // From public templates the correct relative path is "../images/..."
            return '../' . ltrim($normalized, './');
        }

        // If the image already references the images folder (no leading ../)
        // or doesn't exist in the view root, fall back to prefixing images/.
        $candidateInViewImages = __DIR__ . '/../images/' . ltrim($normalized, './');
        if (file_exists($candidateInViewImages)) {
            return '../images/' . ltrim($normalized, './');
        }

        $candidateByBasename = __DIR__ . '/../images/' . $fileName;
        if (file_exists($candidateByBasename)) {
            return '../images/' . $fileName;
        }

        // If nothing matches, return the passed string prefixed with images/ for
        // backward compatibility (templates may expect this).
        return '../images/' . $fileName;
    }
}

if (!function_exists('view_safe_text')) {
    function view_safe_text($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}
