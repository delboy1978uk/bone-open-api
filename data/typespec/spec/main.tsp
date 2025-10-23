import "@typespec/http";
import "@typespec/rest";
import "@typespec/openapi3";

import "./models/index.tsp";
import "./responses/index.tsp";
import "./payloads/index.tsp";

using TypeSpec.Http;
using TypeSpec.Rest;

@service(#{
  title: "Test",
})
@server("/api", "test")
//@useAuth(BearerAuth)
namespace Application;

