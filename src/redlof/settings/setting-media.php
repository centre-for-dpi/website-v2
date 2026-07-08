<?php

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size( 'redlof-thumb-600', 600, 150, true );
add_image_size( 'redlof-thumb-300', 300, 100, true );

/*
to add more sizes, simply copy a line from above
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 100 sized image,
we would use the function:
<?php the_post_thumbnail( 'redlof-thumb-300' ); ?>
for the 600 x 150 image:
<?php the_post_thumbnail( 'redlof-thumb-600' ); ?>

You can change the names and dimensions to whatever
you like. Enjoy!
*/

add_filter( 'image_size_names_choose', 'redlof_custom_image_sizes' );

function redlof_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'redlof-thumb-600' => __('600px by 150px'),
        'redlof-thumb-300' => __('300px by 100px'),
    ) );
}

/*
The function above adds the ability to use the dropdown menu to select
the new images sizes you have just created from within the media manager
when you add media to your content blocks. If you add more image sizes,
duplicate one of the lines in the array and name it according to your
new image size.
*/


