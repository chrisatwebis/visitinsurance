ImageAPI Text
----------

Routines to assist ImageAPI to draw text as part of the ImageAPI or
ImageCache pipeline.

ImageAPI provides an abstracted way to size, crop, and rotate images.
ImageAPI_text provides a matching utility to render text on an image with many
settings.

### To use
The module was designed for use by imagecache_actions (DRUPAL-6--2.x) and other 
(experimental) forks of signwriter, menuwriter, sprites
@see http://drupal.org/project/imagecache_actions

This is a library utility that produces no result on its own. 

It provides features that are used by other modules, and a configuration 
interface to centralize management of FONTS and TEXT STYLES for the various
text rendering utilities.
The configuration UI features dynamic previews of the text styles as you edit.

These settings are at:
  Administer : Site configuration : ImageAPI : ImageAPI Text
  /admin/settings/imageapi/text/settings

### Text Styles
Text styles can be administered there, then invoked in different contexts 
by other modules.
For a full description of text styles and effects, see 
imageapi_text/help/syntax_reference.html 

The styles are defined in a css-like way, and so far support effects like:
[ 
  fill, fill-opacity, font-size, stroke, stroke-opacity, text-shadow, 
  padding (individual sides), 
  positioning (top,left,right,bottom, center),
  rotation, custom effects, background-color, background-opacity,
  white-space (word-wrap), text-align
]
 
For accessibility and design choice, a number of rendering options are 
available for displaying the image on the page, from straight img tag 
replacement to several methods of css replacement.
 
### FOR DEVELOPERS: 
Text images can be created on the fly by calling
imageapi_image_create_text(), which will return an imageapi image object you
can then save, manipulate or return.

HTML for rendering text can be produced by calling
theme_imageapi_text(), which will return HTML that displays the rendered text, 
using any one of the supported methods.
 
To integrate text configurations in your own module, you can embed
imageapi_text_style_widget() into your own form. 

A visual test suite demonstrating many styles and effects is provided.
Visit /admin/settings/imageapi/text/settings/test
for a demo.

Maintainer
----------
- Dan Morrison (dman)
