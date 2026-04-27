================================================================================
CSS LEARNING GUIDE - addresses.css
================================================================================

This guide explains the CSS code used in the addresses page, written in a way that
beginners can understand each property and concept.

================================================================================
UNDERSTANDING CSS BASIC STRUCTURE
================================================================================

/* This is a CSS comment - describes what the code does */

/* 
 * SELECTOR {
 *     PROPERTY: value;
 * }
 *
 * Example:
 */
.address-container {
    display: grid;
}

/* 
 * Translation:
 * - SELECTOR (.address-container) = "Find the HTML element with class='address-container'"
 * - PROPERTY (display) = "Change how it's shown"
 * - VALUE (grid) = "Show items in a grid layout"
 */

================================================================================
LAYOUT PROPERTIES EXPLAINED
================================================================================

-------------------------------------------------------------------------------
1. display: grid
-------------------------------------------------------------------------------
Purpose: Creates a grid layout (rows and columns)

Values:
- display: block        = Elements stack vertically (default for divs)
- display: inline       = Elements sit next to each other (like span)
- display: flex         = Flexible layout - great for centering
- display: grid         = Grid layout - rows and columns
- display: none        = Hide the element completely

Example:
    .addresses-container {
        display: grid;        /* Use grid layout */
        grid-template-columns: 1fr 380px;  /* Two columns: flexible + 380px */
        gap: 2rem;             /* Space between grid items */
    }

-------------------------------------------------------------------------------
2. display: flex
-------------------------------------------------------------------------------
Purpose: One-dimensional layout (row OR column)

Common flex properties:
    .address-actions {
        display: flex;       /* Turn on flexbox */
        gap: 0.75rem;         /* Space between items */
    }

Flexbox alignment:
    justify-content: center;    /* Horizontal centering (left-right) */
    justify-content: space-between; /* Spread items evenly */
    align-items: center;        /* Vertical centering (top-bottom) */

-------------------------------------------------------------------------------
3. position: relative/absolute
-------------------------------------------------------------------------------
Purpose: Control element position

Common values:
    .address-card {
        position: relative;   /* Parent - positioned relative to its normal position */
    }

    .default-badge {
        position: absolute; /* Child - positioned relative to parent */
        top: 1rem;           /* Distance from parent's top edge */
        right: 1rem;         /* Distance from parent's right edge */
    }

Position values:
- position: static      = Default position (where HTML puts it)
- position: relative    = Position relative to normal position
- position: absolute   = Position relative to nearest positioned parent
- position: fixed       = Position relative to browser window (stays on scroll)
- position: sticky      = Sticks to position when scrolling

================================================================================
SPACING PROPERTIES EXPLAINED
================================================================================

-------------------------------------------------------------------------------
1. margin vs padding
-------------------------------------------------------------------------------
Purpose: Add space around/inside elements

    margin-top: 1rem;      /* Space OUTSIDE the element (pushes other elements away) */
    margin-bottom: 1rem;
    margin-left: 1rem;
    margin-right: 1rem;
    
    padding: 1rem;         /* Space INSIDE the element (pushes content away) */

Shorthand:
    margin: 1rem;              /* All sides 1rem */
    margin: 1rem 2rem;         /* Top/bottom 1rem, left/right 2rem */
    margin: 1rem 2rem 3rem 4rem; /* Top right bottom left (clockwise) */

Common units:
- px    = Pixels (fixed size) - 1rem = 16px typically
- rem   = Root em (relative to font size) - recommended!
- %     = Percentage of parent element
- em    = Relative to element's font size
- vw/vh = Percentage of viewport width/height

-------------------------------------------------------------------------------
2. gap
-------------------------------------------------------------------------------
Purpose: Space between flex/grid items

    gap: 1rem;     /* Same as margin for grid/flex children */

================================================================================
STYLING PROPERTIES EXPLAINED
================================================================================

-------------------------------------------------------------------------------
1. Background and Colors
-------------------------------------------------------------------------------
    .address-card {
        background: var(--white);          /* Background color */
        background: #ffffff;             /* Hex color code */
        background: rgb(255, 255, 255); /* RGB color */
        background: rgba(0, 0, 0, 0.5); /* RGBA with transparency */
        background: transparent;           /* No background */
    }

-------------------------------------------------------------------------------
2. Borders
-------------------------------------------------------------------------------
    .address-card {
        border: 1px solid var(--border);         /* Width style color */
        border: 2px solid var(--primary);     /* Solid border */
        border: none;                       /* No border */
        border-radius: 12px;              /* Rounded corners */
        border-radius: 50%;                /* Makes a circle! */
    }

Border styles:
- solid  = Solid line
- dashed = Dashed line
- dotted = Dotted line
- double = Double line

-------------------------------------------------------------------------------
3. Text Styling
-------------------------------------------------------------------------------
    .address-name {
        color: var(--text-dark);       /* Text color */
        font-size: 1.1rem;           /* Text size */
        font-weight: 600;            /* Boldness (100-900) */
        font-family: Arial, sans-serif; /* Font type */
        text-align: center;            /* Text alignment */
        text-transform: uppercase;     /* ALL CAPS */
        line-height: 1.5;           /* Space between lines */
    }

===============================================================================
SIZE PROPERTIES EXPLAINED
==============================================================================

    .address-card {
        width: 100%;           /* Element width */
        height: auto;         /* Element height (auto adapts to content) */
        min-height: 100px;    /* Minimum height */
        max-width: 500px;      /* Maximum width */
    }

Shorthand for size:
    .address-actions .btn {
        padding: 0.5rem 1rem;   /* Vertical horizontal */
    }

===============================================================================
TRANSITION/ANIMATION PROPERTIES EXPLAINED
==============================================================================

    .address-actions .btn {
        transition: all 0.2s;   /* Animate all properties over 0.2 seconds */
        
        /* Or animate specific properties: */
        transition: background 0.2s, color 0.2s;
    }

Common transitions:
- transition: all 0.2s      = Any change over 0.2s
- transition: color 0.2s      = Only color changes
- transition: transform 0.3s   = Movement/scale changes

================================================================================
THE CSS CODE BREAKDOWN (addresses.css)
================================================================================

/*
 * .addresses-container
 * -------------
 * The main wrapper that holds everything
 */
.addresses-container {
    display: grid;                      /* Use grid layout */
    grid-template-columns: 1fr 380px;    /* Two columns: 1 fraction + 380px */
    gap: 2rem;                        /* Space between grid items */
    margin-top: 2rem;                 /* Space above the container */
}

/*
 * .address-card
 * -------------
 * Individual address cards
 */
.address-card {
    background: var(--white);        /* White background */
    border-radius: 12px;             /* Rounded corners */
    padding: 1.5rem;                /* Internal spacing */
    border: 1px solid var(--border);  /* Light border */
    position: relative;              /* For positioning badge inside */
}

/*
 * .address-card.default
 * -------------
 * Style for the DEFAULT address (has special styling)
 */
.address-card.default {
    border: 2px solid var(--primary); /* Thicker primary-colored border */
}

/*
 * .default-badge
 * -------------
 * "DEFAULT" text badge that appears on default address
 */
.default-badge {
    position: absolute;             /* Position in corner */
    top: 1rem;                      /* Distance from top */
    right: 1rem;                   /* Distance from right */
    background: var(--primary);      /* Primary color background */
    color: var(--white);            /* White text */
    padding: 0.25rem 0.75rem;       /* Small padding */
    border-radius: 4px;             /* Slightly rounded */
    font-size: 0.7rem;              /* Small font */
    font-weight: 600;              /* Bold */
    text-transform: uppercase;      /* Make text ALL CAPS */
}

/*
 * .address-details
 * -------------
 * Container for address text
 */
.address-details {
    margin-bottom: 1rem;           /* Space below details */
}

.address-name {
    font-weight: 600;              /* Bold name */
    font-size: 1.1rem;            /* Larger text */
    color: var(--text-dark);        /* Dark text color */
    margin-bottom: 0.25rem;        /* Space below name */
}

/*
 * .address-phone, .address-text, etc.
 * -------------
 * Styling for different text elements
 */
.address-phone {
    color: var(--text-muted);       /* Gray/muted color */
    font-size: 0.9rem;            /* Slightly smaller */
    margin-bottom: 0.5rem;        /* Space below */
}

.address-text {
    color: var(--text-dark);       /* Dark color */
    line-height: 1.5;           /* Line spacing */
    margin-bottom: 0.25rem;
}

.address-city {
    color: var(--text-muted);
    font-size: 0.9rem;
}

/*
 * .address-actions
 * -------------
 * Container for Edit/Delete buttons
 */
.address-actions {
    display: flex;                 /* Buttons in a row */
    gap: 0.75rem;              /* Space between buttons */
    padding-top: 1rem;          /* Space above buttons */
    border-top: 1px solid var(--border); /* Line separating from details */
}

/*
 * Button styles inside address-actions
 */
.address-actions .btn {
    padding: 0.5rem 1rem;       /* Button padding */
    border-radius: 6px;        /* Rounded corners */
    font-size: 0.8rem;          /* Small font */
    font-weight: 500;           /* Medium bold */
    cursor: pointer;             /* Show hand cursor on hover */
    transition: all 0.2s;     /* Smooth hover transition */
    text-decoration: none;     /* Remove underline */
    display: inline-flex;       /* Flex for centering */
    align-items: center;
    justify-content: center;
    border: none;              /* No border by default */
    background: #f5f5f5;     /* Light gray background */
    color: var(--text-dark);    /* Dark text */
}

/*
 * Hover state - when mouse is over button
 */
.address-actions .btn:hover {
    background: #e0e0e0;      /* Darker gray on hover */
}

/*
 * Specific button: Delete button
 */
.address-actions .btn-delete {
    background: transparent;     /* Transparent background */
    border: 1px solid var(--error); /* Red border */
    color: var(--error);         /* Red text */
}

/*
 * Hover for delete button
 */
.address-actions .btn-delete:hover {
    background: var(--error);     /* Red background */
    color: var(--white);        /* White text */
}

/*
 * .address-form-card
 * -------------
 * The add/edit form card
 */
.address-form-card {
    background: var(--white);
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid var(--border);
    height: fit-content;        /* Height fits content */
    position: sticky;          /* Stays visible when scrolling */
    top: 2rem;               /* 2rem from top when scrolling */
}

/*
 * .form-group
 * -------------
 * Form input groups
 */
.form-group {
    margin-bottom: 1rem;      /* Space between inputs */
}

.form-group label {
    display: block;          /* Labels on their own line */
    font-size: 0.85rem;       /* Small label */
    font-weight: 500;        /* Bold label */
    color: var(--text-dark);
    margin-bottom: 0.5rem;   /* Space below label */
}

/*
 * Form inputs and textareas
 */
.form-group input,
.form-group textarea {
    width: 100%;             /* Full width of parent */
    padding: 0.75rem 1rem;   /* Internal spacing */
    border: 1px solid var(--border); /* Border */
    border-radius: 8px;      /* Rounded corners */
    font-size: 0.95rem;      /* Font size */
    font-family: inherit;     /* Use same font as page */
    transition: border-color 0.2s; /* Animate border on focus */
}

/*
 * Focus state - when typing in input
 */
.form-group input:focus,
.form-group textarea:focus {
    outline: none;            /* Remove default outline */
    border-color: var(--primary); /* Primary color border */
}

/*
 * .form-row
 * -------------
 * Two-column form layout
 */
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr; /* Two equal columns */
    gap: 1rem;                      /* Space between */
}

/*
 * .checkbox-label
 * -------------
 * Checkbox + label inline
 */
.checkbox-label {
    display: flex;            /* Side by side */
    align-items: center;       /* Center vertically */
    gap: 0.5rem;            /* Space between checkbox and text */
    cursor: pointer;        /* Hand cursor */
    font-size: 0.9rem;
}

.checkbox-label input {
    width: 18px;            /* Checkbox size */
    height: 18px;
}

/*
 * .empty-state
 * -------------
 * Shown when no addresses exist
 */
.empty-state {
    text-align: center;           /* Center text */
    padding: 3rem;              /* Padding */
    background: var(--white);
    border-radius: 12px;
    border: 1px solid var(--border);
}

.empty-icon {
    font-size: 3rem;          /* Large emoji/icon */
    margin-bottom: 1rem;
}

.empty-state h3 {
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: var(--text-muted);
}

/*
 * ========================================================================
 * RESPONSIVE DESIGN - Media Queries
 * ========================================================================
 * 
 * These apply different styles based on screen size
 * 
 * Common breakpoints:
 * - 1200px = Large desktop
 * - 900px  = Tablet landscape
 * - 768px  = Tablet portrait
 * - 600px  = Large phone
 * - 480px  = Small phone
 */

/* When screen is 900px or less */
@media (max-width: 900px) {
    .addresses-container {
        grid-template-columns: 1fr;  /* Single column */
    }
    
    .address-form-card {
        position: static;         /* Stop sticky */
        order: -1;                /* Show first */
    }
}

/* When screen is 600px or less */
@media (max-width: 600px) {
    .form-row {
        grid-template-columns: 1fr; /* Single column */
    }
    
    .address-actions {
        flex-wrap: wrap;          /* Wrap buttons if needed */
    }
}

/*
 * ========================================================================
 * CSS VARIABLES (CSS Custom Properties)
 * ========================================================================
 * 
 * These are defined elsewhere (usually in main.css):
 * 
 * :root {
 *     --primary: #e83e8c;        /* Main brand color (pink) */
 *     --primary-dark: #c94e7a;   /* Darker pink for hover */
 *     --white: #ffffff;         /* White */
 *     --border: #e5e5e5;     /* Light gray border */
 *     --text-dark: #333333;     /* Dark text */
 *     --text-muted: #666666;   /* Gray text */
 *     --error: #dc3545;       /* Red for errors */
 * }
 *
 * Using var(--name) references the value
 */