export function removeRedirectUri(path: string): string {
  const [pathWithoutQuery, queryString] = path.split('?');

  if (!queryString) {
    return pathWithoutQuery;
  }

  const queryParams = queryString.split('&').filter(param => {
    const [key] = param.split('=');
    return key !== 'redirectUri';
  });

  if (queryParams.length === 0) {
    return pathWithoutQuery;
  }

  const updatedQueryString = queryParams.join('&');
  return `${pathWithoutQuery}?${updatedQueryString}`;
}
