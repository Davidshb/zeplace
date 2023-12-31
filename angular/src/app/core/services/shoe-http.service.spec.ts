import { TestBed } from '@angular/core/testing';

import { ShoeHttpService } from './shoe-http.service';

describe('ShoeHttpService', () => {
  let service: ShoeHttpService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(ShoeHttpService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
