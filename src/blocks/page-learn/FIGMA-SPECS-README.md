# Why Figma values vs fetched values differ – and how to fix it

## Root cause

1. **What we query**
   - We call `get_variable_defs(fileKey, nodeId)` and `get_design_context(fileKey, nodeId)` on the **section/frame** nodes you shared (e.g. desktop `5837:13710`, mobile `5839:16023`).

2. **What `get_variable_defs` returns**
   - It only returns **design token definitions** used in that node’s subtree, e.g.:
     - `"Margins": "24"` (or `"90"` on desktop)
     - `"B2": "Font(..., size: 15, ...)"`
     - `"Secondary H4": "Font(..., size: 12, ...)"`
   - It does **not** return:
     - The **padding** or **margin** of a specific frame (e.g. the card content area) when those are set as **fixed values** (e.g. 16px) in Figma.
     - So card-inner padding/margin are **never** in the variable_defs response if they’re not bound to a variable in Figma.

3. **What `get_design_context` returns**
   - It can return generated code (HTML/CSS) that may include layout (padding, margin) for the requested node.
   - In practice, the **full response body** (the actual CSS/code) is not always visible in the assistant context – only the “IMPORTANT: After this call…” message is seen, so we cannot reliably “see” the padding/margin the API would return for the card.

4. **What we did wrong**
   - We used **section-level** variables (e.g. “Margins” = 24) and assumed they apply to **card content** padding. In Figma, the card’s internal padding is often set on the **card frame** (or content frame) as a **fixed value** (e.g. 16px, 20px), which never appears in `get_variable_defs`.
   - We never had the **exact node ID** of the card (or the frame that has the padding you see in the Inspect panel), so we couldn’t query that node and use its real padding.

## How to fix it

### Option A – Use exact node IDs (recommended)

1. In Figma, select the **exact element** whose specs you want (e.g. the frame that wraps the card content and has the padding you see).
2. Copy its **node ID** (from the URL when you “Copy link” on that node, or from the design panel).
3. Share that node ID (e.g. “Card content frame: 5837:13XXX”).
4. We call `get_design_context(fileKey, thatNodeId)` and use the padding/margin/font from the **returned code** in the theme. If the response still doesn’t show layout, we use Option B.

### Option B – Copy from Figma Inspect panel

1. In Figma, select the frame/text layer and open the **Inspect** panel (right panel).
2. Note the **exact** values for padding, margin, font size, etc.
3. Share those values (e.g. “Card content padding: 16px all sides”).
4. We set them in the theme CSS (in rem) and add a comment: “From Figma Inspect – [element]”.

### Chapter card – node IDs (source of truth)

- **Desktop card:** `5837:14620` – [Figma](https://www.figma.com/design/Gtba3mhsQnCcNnkcCugEHo/CDPI---Website?node-id=5837-14620)
- **Mobile card:** `5839:16069` – [Figma](https://www.figma.com/design/Gtba3mhsQnCcNnkcCugEHo/CDPI---Website?node-id=5839-16069)

Call `get_design_context(fileKey, "5837:14620")` and `get_design_context(fileKey, "5839:16069")` to fetch card layout. `get_variable_defs` on these nodes returns `{}` (card uses fixed padding, not variables). Use the returned code’s padding/margin in the theme, or set from Figma Inspect and comment in CSS.

- **Badge:** 16px confirmed. Other specs from badge layer or Inspect.
- **Heading:** 19px confirmed. Padding/margin from card node or Inspect.
- **Card content padding/margin:** From card node 5837:14620 / 5839:16069 (design context or Inspect).
