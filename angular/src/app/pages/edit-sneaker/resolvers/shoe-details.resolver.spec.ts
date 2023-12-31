import { TestBed } from '@angular/core/testing';
import { ResolveFn } from '@angular/router';

import { shoeDetailsResolver } from './shoe-details.resolver';

describe('sneakerResolver', () => {
  const executeResolver: ResolveFn<boolean> = (...resolverParameters) =>
      TestBed.runInInjectionContext(() => shoeDetailsResolver(...resolverParameters));

  beforeEach(() => {
    TestBed.configureTestingModule({});
  });

  it('should be created', () => {
    expect(executeResolver).toBeTruthy();
  });
});
