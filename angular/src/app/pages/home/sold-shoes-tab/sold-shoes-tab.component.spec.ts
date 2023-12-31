import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SoldShoesTabComponent } from './sold-shoes-tab.component';

describe('SoldShoesTabComponent', () => {
  let component: SoldShoesTabComponent;
  let fixture: ComponentFixture<SoldShoesTabComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [SoldShoesTabComponent]
    });
    fixture = TestBed.createComponent(SoldShoesTabComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
