function el_id(id)
{
  if (document.getElementById)
    return document.getElementById(id);
  else if (window[id])
    return window[id];
  return null;
}


