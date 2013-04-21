/*
 *  header_parser.c
 *
 *  Written by Mr. Russell T. Gaskey 
 *
 *  Date:  21-6-2011
 */
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <ctype.h>

#define SIZE      256
#define RGB_SPACE 3
#define TRUE      1
#define FALSE     0

/* Function prototypes */
void parse_header(char *magic_num, int *width, int *height, int *max_rgb);
int is_valid_id(char *buf); 
void check_for_correct_num_vals(int count); 
int safe(char *line);

/*
 *  main.c
 *
 *  This function is the entry point into the program.
 *  
 *  Params:
 *      argc    - The number of command line arguments, including the name of 
 *                the program.
 *      argv    - The array of command line arguments (argv[0] being the
 *                program itself.
 *
 *  Return:
 *      EXIT_SUCCESS if function runs to completion, EXIT_FAILURE otherwise.
 */
int main(int argc, char *argv[]) {
    unsigned char *src_pixmap;              /* Color values for input image */
    unsigned char *dest_pixmap;             /* Color values for output image*/
    char          magic_num[3] = {0, 0, 0}; /* ID in the .ppm header        */
    double        scale_fact   = 0.0;
    int           width;                    /* Width of image (in pixels)   */
    int           height;                   /* Height of image (in pixels)  */
    int           max_rgb;                  /* Maximum RGB value            */

    parse_header(magic_num, &width, &height, &max_rgb);
    
    if (width < height) {
        scale_fact = 500 / height;
    } else {
        scale_fact = 500 / width;
    }
    
    width  = width * scale_fact;
    height = height * scale_fact;

    fprintf(stdout, "%dx%d", width, height);

    return(EXIT_SUCCESS);
}

/*
 *  parse_header
 *
 *  This function parses the header of a .ppm file extracting the Magic Number,
 *  Width, Height, and Maximum RGB value of the image.
 *
 *  Parameters:
 *      magic_num    - The ptr in which to store the parsed magic number.
 *      width        - The ptr in which to store the parsed width.
 *      height       - The ptr in which to store the parsed height.
 *      max_rgb      - The ptr in which to store the parsed maximum rgb value.
 */
void parse_header(char *magic_num, int *width, int *height, int *max_rgb) {
    int  vals[6];               /* Hold rows and columns                */
    int  count    = 0;          /* Items read in                        */
    char *buf     = NULL;       /* A line of header info                */
    int  rc;                    /* Read counter                         */

    buf = (char *)malloc(SIZE * sizeof(char));  

    fgets(buf, SIZE, stdin);

    /* Determine if the first two characters in the line are a valid ID */
    if (!is_valid_id(buf)) {
        fprintf(stderr, "Invalid header ID -- %s\n", buf);
        exit(EXIT_FAILURE);
    }

    memcpy(magic_num, buf, 2);

    /* See if our first line is valid, going past the P6 */
    if (!safe(buf + 2)) {
        fprintf(stderr, "Invalid line *%s*\n", buf);
        exit(EXIT_FAILURE);
    }

    /* Try to get any numeric values that follow the tag */
    rc = sscanf(buf + 2, "%d %d %d", vals, vals + 1, vals + 2);

    if (rc > 0) {
        count = rc;
    }

    /* Loop until we have 3 values or run out of data */
    while (count < 3) {
        /* Break out of loop on end of file */
        if (fgets(buf, SIZE, stdin) <= 0) {
            break;
        }

        if (!safe(buf)) {
            fprintf(stderr, "Invalid line *%s*\n", buf);
            exit(EXIT_FAILURE);
        }

        rc = sscanf(buf, "%d %d %d %d", vals + count, vals + count + 1, 
                    vals + count + 2, vals + count + 3);

        if (rc > 0)
            count += rc;
    }
    
    check_for_correct_num_vals(count);

    *width     = vals[0];
    *height    = vals[1];
    *max_rgb   = vals[2];

    free(buf);
}

/*
 *  is_valid_id
 *  
 *  This fuction determines whether the header ID is valid or not.
 *
 *  Parameters:
 *      *buf    - The line of header input to be checked.
 *
 *  Return:
 *      TRUE if buf contains a valid header ID, FALSE otherwise.
 */
int is_valid_id(char *buf) {
    int valid = TRUE;   /* Represent a boolean value for a valid header ID  */
    int num;            /* The number associated with the id                */ 

    if (*buf != 'P' || !isdigit(*(buf + 1))) {
        valid = FALSE;
    } else {
        sscanf(buf + 1, "%d", &num);

        if (num != 6) {
            valid = FALSE;
        }
    }

    return valid;
}

/*
 *  check_for_correct_num_vals
 *
 *  Checks to see if the number of values parsed is valid (3 values).
 *
 *  Parameters:
 *      count       - The number of values parsed.
 */
void check_for_correct_num_vals(int count) {
    if(count > 3) {
        fprintf(stderr, "Error: Too many items read in from file\n");
        exit(2);
    } else if (count < 3) {
        fprintf(stderr, "Error: Too few items read in from file\n");
        exit(3);
    }
}

/**
 *  safe
 *
 *  Checks lines in a ppm header for badness like extra characters 
 *  not prefaced by the '#' start of comment character
 * 
 *  Parameters:
 *      *line   -pointer to space holding current input line
 * 
 *  Return:
 *      true if the line is deemed safe, false otherwise
 */
int safe(char *line) {
   char *buf = line;    /* Duplicate start of line */
   int count = 0;       /* Counter i               */

   while (buf && *buf != '\n' && count++ < SIZE) {
      if (!isdigit(*buf) && !isspace(*buf)) {
         if (*buf == '#') {
            return TRUE;

         } else {
            return FALSE;
         }
      }

      buf++;
   }

   return TRUE;
}
