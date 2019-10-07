# GridProject
Class for creating an automatically responsive grid

This class is used to create a grid of like items such as thumbnails for a catalog or a photo gallery. It creates a grid of an arbitrary number of columns and breakpoints (CSS media query breakpoints) specified by the viewport width of any device. This class also creates the necessary CSS to acompany the generated grid. 

*1. pass an array of data to the grid object (any HTML, text, etc).
*2. set the breakpoints for the grid (associative array with the column count as the key and the taeget viewport width as its value.
*3. specify a css classname base for the grid.
*4. instruct the grid to generate the appropriate CSS.
*5. That's it. 
